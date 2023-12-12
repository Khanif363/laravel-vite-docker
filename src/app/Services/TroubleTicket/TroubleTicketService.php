<?php

namespace App\Services\TroubleTicket;

use Exception;
use Carbon\Carbon;
use App\Helpers\Log;
use App\Jobs\EmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Services\ChangeManage\ChangeManageService;
use App\Services\UserManagement\UserManagementService;
use App\Repositories\TroubleTicket\TroubleTicketRepository;
use Illuminate\Support\Facades\Log as FacadesLog;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TroubleTicketService implements TroubleTicketServiceInterface
{
    protected $mainRepository;
    protected $userService;
    protected $changeManageService;

    public function __construct(TroubleTicketRepository $mainRepository, UserManagementService $userService, ChangeManageService $changeManageService)
    {
        $this->mainRepository = $mainRepository;
        $this->userService = $userService;
        $this->changeManageService = $changeManageService;
    }

    // Get all ticket
    public function getTicketAll(object $request): object
    {
        $department = null;

        $rolepermission = $this->getRolePermission();
        if (!in_array('View All Ticket', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && (($rolepermission->role->role_name ?? null) != 'Admin')) :
            $part_permission = ['name' => 'View List Ticket'];
            $rolepermission = $this->getRolePermission($part_permission);
            $permission_depart = $rolepermission->accessRights->pluck('department_id')->toArray();
            $department = $permission_depart;
        endif;

        $data = $this->mainRepository->getTicketAll($request, $department);
        return $data;
    }

    // Get permission for authorization
    public function getRolePermission($part_permission = '')
    {
        $rolepermission = $this->userService->rolePermission($part_permission);
        return $rolepermission;
    }

    public function detailTicket(int $id): object
    {
        $data = $this->mainRepository->detailTicket($id);

        $type = '-';

        // match data for datacom/ip core department
        if (!empty($data->mantoolsDatacom)) {
            if ($data->mantoolsDatacom->datacom_perangkat_id) {
                $type = 'perangkat';
            } else if ($data->mantoolsDatacom->datacom_metro_e_id) {
                $type = 'metro e';
            } else if ($data->mantoolsDatacom->datacom_vpn_ip_id) {
                $type = 'vpn ip';
            } else if ($data->mantoolsDatacom->datacom_ip_transit_id) {
                $type = 'ip transit';
            } else if ($data->mantoolsDatacom->datacom_astinet_id) {
                $type = 'astinet';
            }
            $data->mantoolsDatacom->data_backhaul = $data->mantoolsDatacom->data_backhaul . " - " . "$type";
        }

        return $data;
    }

    // calculate the progress time of data
    public function timeProgress(int $id)
    {

        $data = $this->mainRepository->timeProgress($id);
        $pivot = [];
        foreach ($data->toArray() as $key => $value) {
            if ($value != null)
                $pivot[$key] = $value;
        }

        $pivotKeys = array_keys($pivot);
        $pivotValues = array_values($pivot);

        $timeDiff = array_map(function ($value, $key) use ($pivotValues) {
            if ($key === 0) {
                return 0;
            }
            $diff = strtotime($value) - strtotime($pivotValues[$key - 1]);

            $days = floor($diff / 86400);
            $diff = $diff % 86400;

            // Menghitung jam
            $hours = floor($diff / 3600);
            $diff = $diff % 3600;

            // Menghitung menit
            $minutes = floor($diff / 60);
            $seconds = $diff % 60;

            // Get days, hour, minute, seconds
            $time = ($days != 0 ? $days . " Hari" : '') . ($hours != 0 ? ($days != 0 ? ", " : '') . $hours . " Jam" : '') . ($minutes != 0 ? ($hours != 0 ? ", " : ($days != 0 ? ', ' : '')) . $minutes . " Menit" : '') . ($seconds != 0 ? ($minutes != 0 ? ", " : ($hours != 0 ? ', ' : ($days != 0 ? ', ' : ''))) . $seconds . " Detik" : '');

            return $time > 0 ? $time : 0;
        }, $pivotValues, array_keys($pivotValues));

        $recapTime = array_combine(array_map(function ($key) {
            return str_replace('ttp_', 'diff_', $key);
        }, $pivotKeys), $timeDiff);
        array_shift($recapTime);

        return $recapTime;
    }

    public function findById(int $id)
    {
        $data = $this->mainRepository->findById($id);
        return $data;
    }

    public function findByNomor(string $nomor)
    {
        $data = $this->mainRepository->findByNomor($nomor);
        return $data;
    }

    public function exportTicket(object $request): object
    {
        $department = null;

        $rolepermission = $this->getRolePermission();
        if (!in_array('View All Ticket', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && (($rolepermission->role->role_name ?? null) != 'Admin')) :
            $part_permission = ['name' => 'View List Ticket'];
            $rolepermission = $this->getRolePermission($part_permission);
            $permission_depart = $rolepermission->accessRights->pluck('department_id')->toArray();
            $department = $permission_depart;
        endif;

        $data = $this->mainRepository->exportTicket($request, $department);

        return $data;
    }

    public function createTicket(object $request, $nomor_ticket = '')
    {
        $rolepermission = $this->getRolePermission();
        if (!in_array('Create Ticket', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && (($rolepermission->role->role_name ?? null) != 'Admin') && $request->type !== 'Changes') :
            throw new BadRequestException("Anda tidak memiliki akses untuk create Ticket!");
        endif;

        if ($request->type === 'Changes') {
            return $this->changeManageService->createChange($request);
        }


        // Generate nomor ticket when step is 1
        if ($request->step == 1) :
            $now = Carbon::now();
            $year = $now->format('y');
            $month = $now->month;
            $check = $this->mainRepository->model()->count();
            $last = $this->mainRepository->lastData();

            if ($nomor_ticket == '') {
                if ($check == 0) {
                    $order = 100001;
                    $nomor_ticket = '#' . $year . $month . $order;
                } else {
                    $order = (int)substr($last->nomor_ticket, -6) + 1;
                    $nomor_ticket = '#' . $year . $month . $order;
                }
            }

            $checkByNomor = $this->mainRepository->findByNomor($nomor_ticket);
            if (!empty($checkByNomor)) {
                $order = (int)substr($checkByNomor->nomor_ticket, -6) + 1;
                $nomor_ticket = '#' . $year . $month . $order;
                // Recursif to this function until the number not duplicate
                $this->createTicket($request, $nomor_ticket);
            }
            // Then create ticket when number ticket not duplicate
            $ticket = $this->mainRepository->createTicket($request, $nomor_ticket);
            $ticket = $this->mainRepository->findById($ticket['ticket']['id'] ?? null) ?? null;
        else :
            $data_create = $this->mainRepository->createTicket($request);
            $ticket = $this->mainRepository->findById($request->id ?? null) ?? null;
            $attachments = $data_create['attachments'] ?? null;

            // Join the emails for broadcast information
            $email = ['reporter_email' => $request->reporter_email, 'gm_email' => env('EMAIL_GM')];
            foreach(explode(",", env('OTHER_EMAILS')) as $key=>$other) {
                $email['other_gm'.$key] = trim($other);
            }
            $attachment = [];

            if (env('APP_ENV', 'development') === 'production') :
                $user = $this->userService->getByRoleDepartment(['role_id' => 3]);
                foreach ($user as $users) {
                    $email[] = $users->email;
                }
            endif;

            // Attachment join data
            foreach ($attachments as $value) {
                $attachment[] = str_replace('public/', '', $value->url);
            }

            $date = Carbon::parse($ticket->event_datetime);
            $tanggal = $date->locale('id')->isoFormat('dddd, D MMMM YYYY');
            foreach ($email as $key => $email_val) {
                $data1 = [
                    'subject' => $ticket->subject,
                    'data' => [
                        'type' => "create",
                        'title' => "$ticket->nomor_ticket - $ticket->type",
                        'table' => [
                            'ticketId' => $ticket->nomor_ticket ?? "-",
                            'priority' => $ticket->priority ?? "-",
                            'ticketType' => $ticket->type ?? "-",
                            'ticketCategory' => $ticket->category ?? "-",
                            'troubleType' => $ticket->problem_type ?? "-",
                            'incidentTime' => $tanggal ?? "-",
                            'location' => $ticket->eventLocation->name ?? "-",
                            'informationSource' => $ticket->source_info ?? "-",
                            'ticketStatus' => $ticket->status ?? "-",
                        ],
                        'description' => $ticket->problem ?? "-",
                        'attachments' => $attachment ?? [],
                        'ticket'      => $ticket->nomor_ticket ?? "-",
                        'subject'     => $ticket->subject ?? "-",
                        'reportDate'  => $tanggal ?? "-"
                    ],
                    'view'  => $key == 'reporter_email' ? 'emails.notification-customer' : null
                ];
                // Send information via email
                if (in_array($ticket->type, ['Incident TTR', 'Incident Non TTR']) && ($key == 'gm_email' || preg_match('/other_gm\d+/', $key))) {
                    $this->notifTicket($ticket, [$email_val]);
                    // Update status notification for not recursif
                    $this->updateStatusNotifGM($ticket->id);
                    continue;
                }

                if ($key == 'reporter_email') dispatch(new EmailNotification($email_val, $data1));
            }
        endif;

        return $ticket;
    }

    public function notifTicket($ticket, $emails = [])
    {
        $date_report = ($ticket?->event_datetime ?? null) ? Carbon::parse($ticket->event_datetime)->locale('id')->isoFormat('dddd, D MMMM YYYY') : null;
        foreach (($ticket->attachments ?? []) as $val_attach) {
            $attachment[] = str_replace('public/', '', $val_attach->url);
        }
        $data_email = [
            'subject' => $ticket->subject,
            'data' => [
                'type' => "create",
                'title' => "$ticket->nomor_ticket - $ticket->type",
                'table' => [
                    'ticketId' => $ticket->nomor_ticket,
                    'subject' => $ticket->subject,
                    'priority' => $ticket->priority ?? "-",
                    'ticketType' => $ticket->type ?? "-",
                    'ticketCategory' => $ticket->category ?? "-",
                    'troubleType' => $ticket->problem_type ?? "-",
                    'incidentTime' => $date_report ?? "-",
                    'location' => $ticket->eventLocation->name ?? "-",
                    'informationSource' => $ticket->ticketInfo->source_info ?? "-",
                    'ticketStatus' => $ticket->status ?? "-",
                    'lastProgress' => $ticket->lastProgress->update_type ?? "-",
                ],
                'description' => $ticket->problem ?? "-",
                'attachments' => $attachment ?? [],
            ]
        ];

        foreach ($emails as $email) {
        Mail::to($email)->send(new \App\Mail\EmailNotification($data_email));
        }
    }

    public function updateProgress(object $request, int $id)
    {
        $rolepermission = $this->getRolePermission();

        if (!in_array('Update Ticket', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && ($rolepermission->role->role_name ?? null) != 'Admin') :
            $message = "Anda tidak memiliki akses untuk update Ticket!";
            throw new BadRequestException($message);
        endif;

        $ticket = $this->mainRepository->findById($id);
        $emails = ['gm' => env('EMAIL_GM'), 'customer' => $ticket->customer_email];

        if ($request->update_type == 'Dispatch' && $request->department_to_id == $request->department_from_id) :
            $message = "Department tidak boleh sama!";
            throw new BadRequestException($message);
        endif;
        if ($ticket->status == 'Pending' && $request->update_type == 'Pending') :
            $message = "Tidak bisa, sekarang ticket masih dalam Pending!";
            throw new BadRequestException($message);
        endif;
        if ($request->update_type == 'Diagnose' && $this->mainRepository->checkDiagnose($id)->troubleTicketProgress->count() != 0) :
            $message = "Update Diagnose hanya bisa 1x saja!";
            throw new BadRequestException($message);
        elseif (!in_array($request->update_type, ['Diagnose', 'Pending']) && $this->mainRepository->checkDiagnose($id)->troubleTicketProgress->count() == 0 && $ticket->type === 'Incident TTR') :
            $message = "Update Diagnose terlebih dahulu!";
            throw new BadRequestException($message);
        endif;
        if ($ticket->status == 'Closed') :
            $message = "Ticket tidak bisa di update kembali!, Silahkan buat ticket baru!";
            throw new BadRequestException($message);
        endif;

        $progress = $this->mainRepository->updateProgress($request, $id);
        $ticketAfterUpdate = $this->mainRepository->findById($id);

        $engineer = [];
        $rfo = '';
        $solution = '';
        $totalHandlingDuration = '';

        if ($request->update_type == 'Engineer Assignment') :
            $engineer = $progress->engineerUser()->get();
        endif;

        if ($request->update_type == 'Technical Close') :
            $tech_close = $progress->technicalClose()->first();
            $rfo = $tech_close->rfo;
            $handlingDuration = date_diff($progress->inputed_date, $ticketAfterUpdate->created_date);
            $totalHandlingDuration = $handlingDuration ? ($handlingDuration->d != 0 ? "$handlingDuration->d Hari, " : '') . ($handlingDuration->h != 0 ? "$handlingDuration->h Jam, " : '') . ($handlingDuration->i != 0 ? "$handlingDuration->i Menit, " : '') . ($handlingDuration->s != 0 ? "$handlingDuration->s Detik" : '') : '';
            $solution = $request->solution;
        endif;

        $type = strtolower(str_replace(' ', '-', $request->update_type));

        $handleTime = '';
        foreach ($ticketAfterUpdate->troubleTicketProgress as $progress) {
            if ($progress->update_type == 'Technical Close') {
                $handlingDuration = date_diff($progress->inputed_date, $ticketAfterUpdate->created_date);
                $handleTime = $handlingDuration ? ($handlingDuration->d != 0 ? "$handlingDuration->d Hari, " : '') . ($handlingDuration->h != 0 ? "$handlingDuration->h Jam, " : '') . ($handlingDuration->i != 0 ? "$handlingDuration->i Menit, " : '') . ($handlingDuration->s != 0 ? "$handlingDuration->s Detik" : '') : '';
            }
        }

        $data1 = [
            'subject' => $ticketAfterUpdate->subject,
            'data' => [
                'subject' => $ticketAfterUpdate->subject,
                'type' => $type,
                'title' => "$ticketAfterUpdate->nomor_ticket - $ticketAfterUpdate->type",
                'ticket'      => $ticketAfterUpdate->nomor_ticket,
                'department'      => $ticketAfterUpdate->department->name ?? null,
                'service'      => $ticketAfterUpdate->service->name ?? null,
                'device'      => $ticketAfterUpdate->device->name ?? null,
                'content'      => $progress->content ?? null,
                'engineer'    => $engineer,
                'rfo'   => $rfo,
                'handlingDuration' => $handleTime,
                'solution' => $solution,
                'dataTicket' => $ticketAfterUpdate,
                'progressTicket' => $ticketAfterUpdate->troubleTicketProgress,
                'dear' => 'customer'
            ],
            'view'  => 'emails.notification-customer' ?? null
        ];

        foreach ($emails as $key => $email) {
            if ($key == 'customer' && in_array($request->update_type, ['Engineer Assignment', 'Technical Close']) || ($request->status_report_email == 1 && in_array($request->update_type, ['Diagnose', 'Engineer Troubleshoot', 'Monitoring']))) {
                $data1['data']['dear'] = $key;
                $data1['view'] = 'emails.notification-customer';
                dispatch(new EmailNotification($email, $data1));
            }
            if ($key == 'gm' && (in_array($ticketAfterUpdate->type, ['Incident TTR', 'Incident Non TTR']) || (!in_array($ticketAfterUpdate->type, ['Incident TTR', 'Incident Non TTR']) && $request->status_report_email_gm))) {
                $data1['data']['dear'] = $key;
                $data1['view'] = 'emails.trouble-ticket-non-customer';
                dispatch(new EmailNotification($email, $data1));
            }
        }


        if ($request->customer_confirm == 1 && $request->update_type == 'Closed') :
            $this->sendEmailRating($ticketAfterUpdate);
        endif;
        return $progress;
    }

    public function sendEmailRating($ticket, &$success = null, &$failed = null)
    {
        $token = Crypt::encrypt($ticket->id);

        $data = [
            'subject' => "Rating Customer [{$ticket->nomor_ticket}]: {$ticket->subject}",
            'view' => 'emails.rating',
            'data' => [
                'title' => $ticket->subject,
                'ticket' => $ticket->nomor_ticket,
                'subject' => $ticket->subject,
                'token' => $token
            ]
        ];

        $email = $ticket->ticketInfo->email;

        try {
            DB::beginTransaction();

            $this->updateStatusRate($ticket->id);

            Mail::to($email)->send(new \App\Mail\EmailNotification($data));

            DB::commit();

            $success++;
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            $failed++;
        }
    }


    public function pushNotif($request, $id)
    {
        $ticket = $this->mainRepository->model()->find($id);
        $user_id = [];

        if ($request->receiver == 'manager') {
            $user_id = $this->userService->getManager()->pluck('id');
        } elseif ($request->receiver == 'engineer') {
            $user_id = $this->userService->getEngineer()->pluck('id');
        }

        $save = [
            'title' => $request->title,
            'content' => $request->content,
            'creator_id' => auth()->id()
        ];

        foreach ($user_id as $userid) {
            $this->mainRepository->pushNotif($ticket, $save + ['user_id' => $userid]);
        }

        return $ticket;
    }


    public function readNotif($request, $id)
    {

        $req_read = [
            'is_read' => $request->is_read
        ];

        $ticket = $this->mainRepository->readNotif($req_read, $id);

        return $ticket;
    }

    public function getTicketWaitingClose()
    {
        return $this->mainRepository->getTicketWaitingClose();
    }

    public function updateStatusConfirmTicket($id)
    {
        return $this->mainRepository->updateStatusConfirmTicket($id);
    }

    public function updateClosed($id)
    {
        return $this->mainRepository->updateClosed($id);
    }

    public function getRatingNull()
    {
        return $this->mainRepository->getRatingNull();
    }

    public function updateStatusRate($id)
    {
        return $this->mainRepository->updateStatusRate($id);
    }

    public function addRating($rate)
    {
        return $this->mainRepository->addRating($rate);
    }

    public function sendEmail($request, $id)
    {
        if (empty($request)) {
            throw new BadRequestException('Field tidak boleh kosong');
        }
        $data = $this->mainRepository->findByIdDetail($id);
        if (($request->email_receiver ?? null) == 'gm' && $data->resume_gm != 0)
            throw new BadRequestException('Resume sudah dikirim ke GM!');
        if (($request->email_receiver ?? null) == 'cto' && $data->resume_cto != 0)
            throw new BadRequestException('Resume sudah dikirim ke CTO!');

        $email = [];
        $resume_req = [];
        $resume_req_status = 1;

        $request_resume = [
            'inputer_id' => auth()->id(),
            'content' => $request->resume_content
        ];

        if (env('APP_ENV', 'development') === 'production') {
            if ($request->email_receiver == 'manager') {
                // $user_req = [
                //     'department_id' => $data->department_dispatch_id ?? $data->department_id,
                //     'role_id'       => 3
                // ];
                // $user = $this->userService->getByRoleDepartment($user_req);
                // foreach ($user as $users) {
                //     $email[] = $users->email;
                // }
                // $resume_req = ['resume_manager' => 1];
            } elseif ($request->email_receiver == 'gm') {
                $this->mainRepository->saveResume($request_resume, $id);
                $email = [env('EMAIL_GM')];
                $resume_req = ['resume_gm' => $resume_req_status];
            } elseif ($request->email_receiver == 'cto') {
                $email = [env('EMAIL_CTO')];
                $resume_req = ['resume_cto' => $resume_req_status];
            }
        } else {
            if ($request->email_receiver == 'manager') {
            } elseif ($request->email_receiver == 'gm') {
                $this->mainRepository->saveResume($request_resume, $id);
                $resume_req = ['resume_gm' => $resume_req_status];
            } elseif ($request->email_receiver == 'cto') {
                $resume_req = ['resume_cto' => $resume_req_status];
            }
        }

        $this->mainRepository->updateStatusResume($resume_req, $id);
        $date = Carbon::parse($data->event_datetime ?? '');
        $tanggal = $date->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $data1 = [
            'subject' => $data->subject,
            'data' => [
                'type' => "resume",
                'title' => "Resume - $data->nomor_ticket - $data->type",
                'table' => [
                    'ticketId' => $data->nomor_ticket,
                    'department' => $data->department_name,
                    'service' => $data->service_name,
                    'priority' => $data->priority,
                    'ticketType' => $data->type,
                    // 'ticketCategory' => $data->category,
                    'troubleType' => $data->problem_type,
                    'incidentTime' => $tanggal,
                    'location' => $data->event_location,
                    'informationSource' => $data->source_info_trouble,
                    'ticketStatus' => $data->status,
                ],
                'description' => $request->resume_content,
            ]
        ];

        foreach ($email as $email_val) {
            dispatch(new EmailNotification($email_val, $data1));
        }
    }

    public function editProgress($request, $id)
    {
        $request_edit = [
            'editor_id' => auth()->id(),
            'content'   => $request->content
        ];
        return $this->mainRepository->editProgress($request_edit, $id);
    }

    public function editCreate($request, $id)
    {
        $request_edit = [
            'subject'   => $request->content
        ];
        return $this->mainRepository->editCreate($request_edit, $id);
    }

    public function findTicketProgress($id)
    {
        return $this->mainRepository->findTicketProgress($id);
    }

    public function lastDepartment($id)
    {
        return $this->mainRepository::lastDepartment($id);
    }

    public function getAlert($request)
    {
        $department = null;
        $part_permission = ['name' => 'View List Ticket'];
        $rolepermission = $this->getRolePermission($part_permission);
        $permission_depart = $rolepermission->accessRights->pluck('department_id')->toArray();
        if ((($rolepermission->role->role_name ?? null) != 'Admin') && (count($permission_depart) < 4)) :
            $department = $permission_depart;
        endif;

        $count = $this->mainRepository->getAlert($request, $department);


        if ($request->type == 'gangguan') :
            $result = $count ? "$count TICKET GANGGUAN STILL OPEN" : null;
        elseif ($request->type == 'gamas') :
            $result = $count ? "$count TICKET GAMAS STILL OPEN" : null;
        else :
            $result = $count ? "$count TICKET NEED TO DIAGNOSE" : null;
        endif;
        session()->put('last_check', Carbon::now());

        return $result;
    }

    public function getTotalTicket($department)
    {
        $data = $this->mainRepository->getTotalTicket($department);
        return $data;
    }

    public function getMTTR($department)
    {
        $mttr = $this->mainRepository->getMTTR($department);

        $getFloorResult = ['current_month_mttr' => $mttr['mttr_month']['current_mttr'][0]->mttr ?? 0, 'current_week_mttr' => $mttr['mttr_week']['current_mttr'][0]->mttr ?? 0, 'mttr_month' => $mttr['mttr_month']['mttr'] ? $mttr['mttr_month']['mttr']->toArray() : [], 'mttr_week' => (($mttr['mttr_week'] ?? null) ? ($mttr['mttr_week']['mttr'] ? $mttr['mttr_week']['mttr']->toArray() : []) : []) ?? []];

        foreach ($getFloorResult as $key => $resultFloor) {
            if (gettype($resultFloor) == 'array') {
                foreach ($resultFloor as $key_number => $value) {
                    foreach ($value as $key_query => $val) {
                        if ($key_query === 'mttr')
                            $getFloorResult[$key][$key_number][$key_query] = $this->floorTime($val, 'number');
                    }
                }
            } else {
                $getFloorResult[$key] = $this->floorTime($resultFloor);
            }
        }


        $mttr_m = array_map(function ($value) {
            return [
                'label' => $value['month'] ?? '',
                'data' => $value['mttr'] ?? 0,
                'max_ticket_number' => $value['max_ticket_number'] ?? null,
            ];
        }, $getFloorResult['mttr_month']);

        $data = [
            'mttr_month' => [
                'current_mttr' => $getFloorResult['current_month_mttr'],
                'mttr' => $mttr_m ?? [],
            ],
        ];

        if (auth()->user()->role->role_name === 'Admin' || (in_array(auth()->user()->department_id, [2, 4]) && $department === 'IP Core Network')) {
            $mttr_w = array_map(function ($value) {
                return [
                    'label' => $value['week_number'] ?? '',
                    'data' => $value['mttr'] ?? 0,
                    'max_ticket_number' => $value['max_ticket_number'] ?? null,
                ];
            }, $getFloorResult['mttr_week']);

            $data += ['mttr_week' => [
                'current_mttr' => $getFloorResult['current_week_mttr'],
                'mttr' => $mttr_w ?? [],
            ]];
        }

        return $data;
    }

    public function getMTTA()
    {
        $mtta = $this->mainRepository->getMTTA();

        $getFloorResult = ['current_month_mtta' => $mtta['mtta_month']['current_mtta'][0]->mtta ?? 0, 'current_week_mtta' => $mtta['mtta_week']['current_mtta'][0]->mtta ?? 0, 'mtta_month' => $mtta['mtta_month']['mtta'] ? $mtta['mtta_month']['mtta']->toArray() : [], 'mtta_week' => (($mtta['mtta_week'] ?? null) ? ($mtta['mtta_week']['mtta'] ? $mtta['mtta_week']['mtta']->toArray() : []) : []) ?? []];

        foreach ($getFloorResult as $key => $resultFloor) {
            if (gettype($resultFloor) == 'array') {
                foreach ($resultFloor as $key_number => $value) {
                    foreach ($value as $key_query => $val) {
                        if ($key_query === 'mtta')
                            $getFloorResult[$key][$key_number][$key_query] = $this->floorTime($val, 'number');
                    }
                }
            } else {
                $getFloorResult[$key] = $this->floorTime($resultFloor);
            }
        }


        $mtta_m = array_map(function ($value) {
            return [
                'label' => $value['month'] ?? '',
                'data' => $value['mtta'] ?? 0,
                'max_ticket_number' => $value['max_ticket_number'] ?? null,
            ];
        }, $getFloorResult['mtta_month']);

        $data = [
            'mtta_month' => [
                'current_mtta' => $getFloorResult['current_month_mtta'],
                'mtta' => $mtta_m ?? [],
            ],
        ];

        if (auth()->user()->role->role_name === 'Admin' || in_array(auth()->user()->department_id, [2, 4])) {
            $mtta_w = array_map(function ($value) {
                return [
                    'label' => $value['week_number'] ?? '',
                    'data' => $value['mtta'] ?? 0,
                    'max_ticket_number' => $value['max_ticket_number'] ?? null,
                ];
            }, $getFloorResult['mtta_week']);

            $data += ['mtta_week' => [
                'current_mtta' => $getFloorResult['current_week_mtta'],
                'mtta' => $mtta_w ?? [],
            ]];
        }

        return $data;
    }

    public function floorTime($resultFloor, $type = 'text')
    {
        $recap = '';
        switch ($type) {
            case 'number':
                $days = floor($resultFloor / 86400);
                $resultFloor = $resultFloor % 86400;

                // Menghitung jam
                $hours = floor($resultFloor / 3600);
                $resultFloor = $resultFloor % 3600;

                // Menghitung menit
                $minutes = floor($resultFloor / 60);
                $seconds = $resultFloor % 60;

                $hours += (24 * $days);
                $recap = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes) . ":" . sprintf("%02d", $seconds);
                break;
            case 'text':
                $days = floor($resultFloor / 86400);
                $resultFloor = $resultFloor % 86400;

                // Menghitung jam
                $hours = floor($resultFloor / 3600);
                $resultFloor = $resultFloor % 3600;

                // Menghitung menit
                $minutes = floor($resultFloor / 60);
                $seconds = $resultFloor % 60;

                $recap = ($days != 0 ? $days . " Hari" : '') . ($hours != 0 ? ($days != 0 ? ", " : '') . $hours . " Jam" : '') . ($minutes != 0 ? ($hours != 0 ? ", " : ($days != 0 ? ', ' : '')) . $minutes . " Menit" : '') . ($seconds != 0 ? ($minutes != 0 ? ", " : ($hours != 0 ? ', ' : ($days != 0 ? ', ' : ''))) . $seconds . " Detik" : '');
                break;
            default:
                $recap = 0;
        }

        return $recap;
    }

    public function getTopTicket()
    {
        $data = $this->mainRepository->getTopTicket();
        return $data;
    }

    public function getOpenMore3Days()
    {
        $data = $this->mainRepository->getOpenMore3Days();
        return $data;
    }

    public function getRate()
    {
        $data = $this->mainRepository->getRate();
        return $data;
    }

    public function getTicketAfterOneHourCreated()
    {
        return $this->mainRepository->getTicketAfterOneHourCreated();
    }

    public function getMantoolsDatacom()
    {

        $allDatacom = $this->mainRepository->getMantoolsDatacom();

        $datacomResult = [];

        foreach ($allDatacom as $datacom) {

            $type = '-';

            if ($datacom->datacom_perangkat_id) {
                $type = 'perangkat';
            } else if ($datacom->datacom_metro_e_id) {
                $type = 'metro e';
            } else if ($datacom->datacom_vpn_ip_id) {
                $type = 'vpn ip';
            } else if ($datacom->datacom_ip_transit_id) {
                $type = 'ip transit';
            } else if ($datacom->datacom_astinet_id) {
                $type = 'astinet';
            }

            $datacomResult[] = [
                'id' => $datacom->id,
                'name' => "$datacom->customer - $datacom->name - $datacom->location - $type"
            ];
        }

        return $datacomResult;
    }

    public function updateStatusNotifGM(int $id)
    {
        return $this->mainRepository->updateStatusNotifGM($id);
    }

    public function deleteTicket($request, $id)
    {
        return $this->mainRepository->deleteTicket($request, $id);
    }
}
