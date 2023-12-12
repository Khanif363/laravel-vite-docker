<?php

namespace App\Services\ChangeManage;

use Carbon\Carbon;
use App\Models\UserCR;
use App\Jobs\EmailNotification;
use App\Jobs\EmailChangeRequest;
use Illuminate\Support\Facades\Log;
use App\Services\UserManagement\UserManagementService;
use App\Repositories\ChangeManage\ChangeManageRepository;
use App\Repositories\TroubleTicket\TroubleTicketRepository;
use App\Services\ChangeManage\ChangeManageServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ChangeManageService implements ChangeManageServiceInterface
{
    private $mainRepository;
    private $userService;
    private $userCR;
    private $troubleTicketRepository;

    public function __construct(ChangeManageRepository $mainRepository, UserManagementService $userService, UserCR $userCR, TroubleTicketRepository $troubleTicketRepository)
    {
        $this->mainRepository = $mainRepository;
        $this->userService = $userService;
        $this->userCR = $userCR;
        $this->troubleTicketRepository = $troubleTicketRepository;
    }

    public function getCRAll($request): object
    {
        $data = $this->mainRepository->getCRAll($request);
        return $data;
    }

    public function getCRAllOnly($request): object
    {
        $data = $this->mainRepository->getCRAllOnly($request);
        return $data;
    }

    public function detailCR(int $id)
    {
        $data = $this->mainRepository->detailCR($id);
        return $data;
    }

    public function updateByColumn($request, $id)
    {
        $data_request = [];
        $progress = null;

        if ($request->for === 'submit-changes') {
            $data = $this->mainRepository->detailCR($id) ?? null;
            $attach = $data->attachments ?? null;

            $attachments = [];

            $is_picture = ['gif', 'jpg', 'png', 'jpeg'];

            foreach ($attach as $value) {
                $extension = pathinfo($value->name, PATHINFO_EXTENSION);

                if (in_array($extension, $is_picture)) {
                    $attachments['picture'][] = str_replace('public/', '', $value->url);
                } else {
                    $attachments['non_picture'][] = str_replace('public/', '', $value->url);
                }
            }

            $data_email = [];

            if ($data->email_to_level0 == 1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $data->engineer_email : env('EMAIL_TEST'),
                    'subject' => "Submitted CR " . $data->nomor_changes . " - " . $data->title
                ];
                Log::info("Email CR to Engineer");
            endif;
            if ($data->email_to_level1 == 1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $data->approval1_email : env('EMAIL_TEST'),
                    'subject' => "Permohonan verifikasi Manager CR " . $data->nomor_changes . " - " . $data->title
                ];
                Log::info("Email CR to Manager");
            endif;

            $this->sendEmailChanges($data, $attachments, $data_email);


            $data_request['create_progress'] = [
                'change_manage_id' => $id,
                'inputer_id' => auth()->id(),
                'information' => null,
                'inputed_date' => now(),
                'progress_type' => 'Submit',
                'edited' => null,
            ];
            $progress = $this->mainRepository->createProgress($data_request['create_progress']);
        }

        $data_request['update_changes'] = [
            $request->key => $request->value,
            'submited_date' => now(),
            'last_updated_date' => now(),
            'last_progress_id' => $progress->id
        ];
        $data = $this->mainRepository->updateByColumn($data_request['update_changes'], $id);
        return $data;
    }

    public function timeProgress(int $id)
    {
        $data = $this->mainRepository->timeProgress($id);
        return $data;
    }

    public function findById(int $id)
    {
        $data = $this->mainRepository->findById($id);
        return $data;
    }

    public function getRolePermission($part_permission = '')
    {
        $rolepermission = $this->userService->rolePermission($part_permission);
        return $rolepermission;
    }

    public function verifCR(object $request, int $id)
    {
        $problem = $this->mainRepository->findById($id);
        if (($problem->app_noc != null && $request->verif == 'manager') || ($problem->app_2 != null && $request->verif == 'gm')) :
            throw new BadRequestException("Sudah diverifikasi, Tidak perlu diverifikasi ulang!");
        endif;
        $data = $this->mainRepository->verifCR($request, $id);
        return $data;
    }

    public function exportCR($request): object
    {
        $data = $this->mainRepository->exportCR($request);
        return $data;
    }

    public function exportPdfById(int $id): object
    {
        $data = $this->mainRepository->detailCR($id);
        return $data;
    }

    public function createChange($request)
    {

        $rolepermission = $this->getRolePermission();
        if (!in_array('Create Changes', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && (($rolepermission->role->role_name ?? null) != 'Admin')) :
            throw new BadRequestException("Anda tidak memiliki akses untuk create Changes!");
        endif;

        $data_request = [];
        $nomor_changes = '';

        if ($request->reference === 'Ticket') {
            $checkTicket = $this->troubleTicketRepository->findByNomor($request->ticket_reference) ?? null;
            if ($checkTicket === null) {
                throw new BadRequestException("Nomor Ticket tidak ditemukan!");
            }
        }

        if (!$request->id) {
            $now = Carbon::now();
            $year = $now->format('y');
            $month = $now->month;
            $check = $this->mainRepository->model()->count();
            $last = $this->mainRepository->lastData();

            if ($check == 0) {
                $order = 100001;
                $nomor_changes = 'CH' . $year . $month . $order;
            } else {
                $order = (int)substr($last->nomor_changes, -6) + 1;
                $nomor_changes = 'CH' . $year . $month . $order;
            }

            $checkByNomor = $this->mainRepository->findByNomor($nomor_changes);
            if (!empty($checkByNomor)) {
                $order = (int)substr($checkByNomor->nomor_changes, -6) + 1;
                $nomor_changes = 'CH' . $year . $month . $order;
                $this->createChange($request, $nomor_changes);
            }
        }

        $edited = [];
        $current_changes = [];

        if ($request->id) {
            $current_changes = $this->mainRepository->detailCR($request->id);

            $request->title != $current_changes->title ? array_push($edited, "Title") : null;
            $request->type != $current_changes->type ? array_push($edited, "Tipe Changes") : null;
            $request->priority != $current_changes->priority ? array_push($edited, "Prioritas") : null;
            date('Y-m-d H:i:s', strtotime($request->date_action . " " . $request->time_action)) != $current_changes->datetime_action ? array_push($edited, "Date/Time Aksi") : null;
            $request->pic_telkomsat != $current_changes->pic_telkomsat ? array_push($edited, "PIC Telkomsat") : null;
            $request->pic_nontelkomsat != $current_changes->pic_nontelkomsat ? array_push($edited, "PIC Non Telkomsat") : null;
            $request->reference != $current_changes->reference ? array_push($edited, "Referensi Changes") : null;
            $request->location_id != $current_changes->location_id ? array_push($edited, "Location") : null;
            $request->engineer_id != $current_changes->engineer_id ? array_push($edited, "Nama Engineer") : null;
            $request->approval_level1_id != $current_changes->approval_level1_id ? array_push($edited, "Persetujuan Oleh Manager") : null;
            $request->approval_level2_id != $current_changes->approval_level2_id ? array_push($edited, "Persetujuan Oleh GM") : null;
            $request->agenda != $current_changes->agenda ? array_push($edited, "Agenda") : null;
            $request->content != $current_changes->content ? array_push($edited, "Content") : null;
            $request->ticket_reference_id != $current_changes->ticket_reference_id ? array_push($edited, "Ticket Reference") : null;
            ($request->images_removed ?? null) ? array_push($edited, "Attachment") : null;
        }

        $data_request += [
            'step' => 2,
            'id' => $request->id,
            'engineer_id' => $request->engineer_id,
            'create_or_update' => [
                'is_draft' => $request->button_type == 'save' ? 1 : 0,
                'title' => $request->title ?? null,
                'type' => $request->type ?? null,
                'priority' => $request->priority ?? null,
                'datetime_action' => date('Y-m-d H:i:s', strtotime($request->date_action . " " . $request->time_action)),
                'pic_telkomsat' => $request->pic_telkomsat ?? null,
                'pic_nontelkomsat' => $request->pic_nontelkomsat ?? null,
                'reference' => $request->reference ?? null,
                'location_id' => $request->location_id ?? null,
                'engineer_id' => $request->engineer_id ?? null,
                'approval_level1_id' => $request->approval_level1_id ?? null,
                'approval_level2_id' => $request->approval_level2_id ?? null,
                'agenda' => $request->agenda ?? null,
                'content' => $request->content ?? null,
                'step' => $request->step ?? null,
                'email_to_level0' => $request->id && !$current_changes->is_draft ? ($request->engineer_email ?? $current_changes->email_to_level0) : ($request->engineer_email ?? 0),
                'email_to_level1' => $request->id && !$current_changes->is_draft ? ($request->manager_email ?? $current_changes->email_to_level1) : ($request->manager_email ?? 0),
                'email_to_level2' => $request->id && !$current_changes->is_draft ? ($request->gm_email ?? $current_changes->email_to_level2) : ($request->gm_email ?? 0),
                'ticket_reference_id' => $checkTicket->id ?? null,
                'creator_id' => $request->id ? $current_changes->creator_id : $request->creator_id,
                'status' => $request->status ?? 'Open',
                'closed_date' => null,
                'nomor_changes' => !$request->id ? $nomor_changes : $current_changes->nomor_changes,
                'images_removed' => $request?->images_removed ?? null
            ],
        ];

        if ((!$request?->id || $current_changes?->is_draft == 1) && $request->button_type == 'submit') {
            $data_request['create_or_update'] += ['submited_date' => now()];
        }

        if ($request->id ? !$current_changes->is_draft || ($current_changes->is_draft && $request->button_type == 'submit') : false) {
            $data_request +=  [
                'create_progress' => [
                    'inputer_id' => auth()->id(),
                    'information' => $request->id && ($current_changes->lastProgress ? in_array($current_changes->lastProgress->progress_type, ["Approval By Manager", "Approval By GM"]) && ($current_changes->lastProgress->approval ? $current_changes->lastProgress->approval->status_approval != 1 : false) : false) ? $request->progress_content : null,
                    'inputed_date' => now(),
                    'progress_type' => !$current_changes->is_draft ? 'Edit' : 'Submit',
                    'edited' => $edited ?? null,
                ]
            ];
        }

        if ($request->id)
            $data_request['create_or_update'] += ['last_updated_date' => now()];
        $oldData = $request?->id ? ($this->mainRepository->detailCR($request->id) ?? null) : null;

        $data_create = $this->mainRepository->createChange($data_request, $request);
        $data = $this->mainRepository->detailCR($data_create['changes']->id ?? null) ?? null;
        $attach = $data_create['attachments'] ?? null;


        $attachments = [];

        $is_picture = ['gif', 'jpg', 'png', 'jpeg'];

        foreach ($attach as $value) {
            $extension = pathinfo($value->name, PATHINFO_EXTENSION);

            if (in_array($extension, $is_picture)) {
                $attachments['picture'][] = str_replace('public/', '', $value->url);
            } else {
                $attachments['non_picture'][] = str_replace('public/', '', $value->url);
            }
        }

        $data_email = [];

        if ($request->button_type == 'submit' && (($oldData?->lastProgress && in_array($oldData->lastProgress->progress_type, ['Approval By Manager', 'Approval By GM']) && $oldData->lastProgress->approval->status_approval !== 1) || !$oldData?->lastProgress)) :
            if (($request?->engineer_email == 1 && $oldData?->is_draft == 1) || $data->email_to_level0) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $data->engineer_email : env('EMAIL_TEST'),
                    'subject' => "Submitted CR " . $data->nomor_changes . " - " . $data->title
                ];
                Log::info("Email CR to Engineer");
            endif;
            if (($request?->manager_email == 1 && $oldData?->is_draft == 1) || $data->email_to_level1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $data->approval1_email : env('EMAIL_TEST'),
                    'subject' => "Permohonan verifikasi Manager CR " . $data->nomor_changes . " - " . $data->title
                ];
                Log::info("Email CR to Manager");
            endif;
        endif;

        $this->sendEmailChanges($data, $attachments, $data_email);

        return $data_create;
    }

    private function sendEmailChanges(object $data, array $attachments, array $data_email)
    {

        $type = $data->type;
        $type_implode = is_array($type) ? implode(',', $type) : $type;
        $date = Carbon::parse($data->event_datetime ?? '');
        $tanggal = $date->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $waktu = $date->locale('id')->isoFormat('H:m');
        $pic_telkomsat = !empty($data->pic_telkomsat) ? "$data->pic_telkomsat (PIC Telkomsat)" : "";
        $pic_nontelkomsat = !empty($data->pic_nontelkomsat) ? "$data->pic_nontelkomsat (PIC Non Telkomsat)" : "";
        $view_pic = $pic_telkomsat . $pic_nontelkomsat;

        $type_convert = $data->type ? implode(', ', $data->type) : '';

        $data1 = [
            'subject' => '',
            'data' => [
                'type' => "Change Request",
                'title' => $data->nomor_changes . " - " . $type_convert,
                'table' => [
                    'changeRequest' => $data->title,
                    'date' => $tanggal,
                    'time' => $waktu,
                    'location' => $data->location_name,
                    'pic' => $view_pic,
                    'typeOfMaintenance' => $type_implode,
                    'priority' => $data->priority,
                    'agenda' => $data->agenda,
                    'dateTimeAction' => $data->datetime_action
                ],
                'description' => $data->content,
                'attachments' => $attachments['picture'] ?? [],
                'attachments_non_pict' => $attachments['non_picture'] ?? [],
                'route' => route('change-managements.detail', $data->id),

                'status_approval1' => $data->status_approval1 ?? null,
                'status_approval2' => $data->status_approval2 ?? null,
                'engineer' => $data->engineer_name ?? null,
                'inisial_engineer' => $data?->engineer?->karyawan?->inisial,
                'jabatan_engineer' => $data?->engineer?->karyawan?->jabatan?->nama ? $data?->engineer?->karyawan?->jabatan?->nama : 'Engineer',
                'manager' => $data->approval1_name ?? null,
                'inisial_manager' => $data?->approval1?->karyawan?->inisial,
                'jabatan_manager' => $data?->approval1?->karyawan?->jabatan?->nama ? $data?->approval1?->karyawan?->jabatan?->nama : $data?->jabatan_manager,
                'gm' => $data->approval2_name ?? null,
                'inisial_gm' => $data?->approval2?->karyawan?->inisial,
                'jabatan_gm' => $data?->approval2?->karyawan?->jabatan?->nama ? $data?->approval2?->karyawan?->jabatan?->nama : 'GM Information Technology',
                'show_stepbystep' => false
            ]
        ];

        foreach ($data_email ?? [] as $val) {
            $data1['subject'] = $val['subject'];
            dispatch(new EmailChangeRequest($val['email'], $data1));
        }
    }

    public function updateProgress(object $request, int $id)
    {
        $rolepermission = $this->getRolePermission();
        $changes = $this->mainRepository->detailCR($id);
        $data_request = [];
        $progress_type = $request->progress_type;

        if ($changes->status == 'Closed')
            throw new BadRequestException("Ticket tidak bisa di update kembali!, Silahkan buat ticket baru!");

        $data_request += [
            'progress_type' => $progress_type,
            'request' => $request,
            'update_progress_content' => [
                'inputer_id' => auth()->id(),
                'information' => $request->content,
                'inputed_date' => now(),
                'progress_type' => $progress_type,
            ],
            'update_progress_approval' => [
                'inputer_id' => auth()->id(),
                'inputed_date' => now(),
                'progress_type' => $progress_type,
            ],
            'update_changes' => [
                'last_updated_date' => now(),
                'status' => $progress_type == 'Approval By Manager' && ($request->status_approval ?? null) == 1 && $changes->approval_level2_id == null ? 'Closed' : ($progress_type == 'Approval By GM' && ($request->status_approval ?? null) == 1 ? 'Closed' : 'Open'),
                'closed_date' => $progress_type == 'Approval By Manager' && ($request->status_approval ?? null) == 1 && $changes->approval_level2_id == null ? now() : ($progress_type == 'Approval By GM' && ($request->status_approval ?? null) == 1 ? now() : null)
            ],
            'update_approval' => [
                'status_approval' => $request->status_approval ?? null,
                'reason_reject' => $request->reject_content ?? null,
                'inputer_id' => auth()->id(),
                'inputed_date' => now(),
            ]
        ];

        $data_email = [];

        if ($progress_type === 'Approval By Manager') {
            if ($changes->email_to_level0 == 1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $changes->engineer_email : env('EMAIL_TEST'),
                    'subject' => (($request->status_approval ?? null) == 1 ? "Disetujui oleh manager CR " : "Ditolak oleh Manager CR ") . $changes->nomor_changes . " - " . $changes->title
                ];
                Log::info("Email CR to Engineer");
            endif;
            if ($changes->email_to_level2 == 1 && ($request->status_approval ?? null) == 1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $changes->approval2_email : env('EMAIL_TEST'),
                    'subject' => "Permohonan persetujuan GM CR " . $changes->nomor_changes . " - " . $changes->title
                ];
                Log::info("Email CR to GM");
            endif;
        }

        if ($progress_type === 'Approval By GM') {
            if ($changes->email_to_level0 == 1) :
                $data_email[] = [
                    'email' => env('APP_ENV', 'development') === 'production' ? $changes->engineer_email : env('EMAIL_TEST'),
                    'subject' => (($request?->status_approval ?? null) == 1 ? "Disetujui oleh GM CR " : "Ditolak oleh GM CR ") . $changes->nomor_changes . " - " . $changes->title
                ];
                Log::info("Email CR to Engineer");
            endif;
        }

        $attach = $changes->attachments ?? null;
        $attachments = [];

        $is_picture = ['gif', 'jpg', 'png', 'jpeg'];

        foreach ($attach as $value) {
            $extension = pathinfo($value->name, PATHINFO_EXTENSION);

            if (in_array($extension, $is_picture)) {
                $attachments['picture'][] = str_replace('public/', '', $value->url);
            } else {
                $attachments['non_picture'][] = str_replace('public/', '', $value->url);
            }
        }

        $this->sendEmailChanges($changes, $attachments, $data_email);

        if ($progress_type === 'Closed')
            $data_request['update_changes'] += ['closed_date' => now()];

        $data = $this->mainRepository->updateProgress($data_request, $id);
        return $data;
    }

    public function getDatacomLocation(): object
    {
        $data = $this->mainRepository->getDatacomLocation();
        return $data;
    }

    public function getUserCR(): object
    {
        $data = $this->mainRepository->getUserCR();
        return $data;
    }

    public function deleteComment($id)
    {
        $data = $this->mainRepository->deleteComment($id);
        return $data;
    }

    public function deleteChanges($request, $id)
    {
        return $this->mainRepository->deleteChanges($request, $id);
    }
}
