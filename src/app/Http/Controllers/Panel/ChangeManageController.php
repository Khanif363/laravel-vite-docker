<?php

namespace App\Http\Controllers\Panel;

use Exception;
use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\ChangeManage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\EmailChangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exports\ChangeManageExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRCreateRequest;
use App\Http\Requests\CRUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Location\LocationService;
use App\Services\ChangeManage\ChangeManageService;
use App\Services\TroubleTicket\TroubleTicketService;
use App\Services\UserManagement\UserManagementService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

class ChangeManageController extends Controller
{
    private $mainService;
    private $menu;
    private $data_view;
    private $troubleTicketService;
    private $logActivity;
    private $route_prefix = 'change-managements.';
    private $view_prefix = 'pages.change-management.';


    public function __construct(ChangeManageService $mainService, LocationService $locationService, UserManagementService $userManagementService, TroubleTicketService $troubleTicketService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->troubleTicketService = $troubleTicketService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama' => 'Changes',
        ];

        $cr_type = [
            'Improvement',
            'Enhancement',
            'Compliance',
            'Provisioning',
            'Audit Request',
            'Optimation',
            'Corrective Core Network',
        ];

        $priority = [
            'Low',
            'Medium',
            'High'
        ];

        $reference = [
            'Nota Dinas',
            'VIP Memo',
            'Email',
            'MoM',
            'Memo Internal (MI)',
            'Lain-lain'
        ];

        $progress_type = [
            'Engineer Troubleshoot',
            'Closed'
        ];

        $this->data_view = [
            'cr_types' => $cr_type,
            'users'   => $userManagementService->getUserActive(),
            'locations' => $locationService->getLocationAll(),
            'engineers' => $userManagementService->getEngineerManager(),
            'managers' => $userManagementService->getManager(),
            'general_managers' => $userManagementService->getGM(),
            'priorities' => $priority,
            'references' => $reference,
            'progress_types' => $progress_type
        ];
    }

    public function index(Request $request)
    {
        $menu = $this->menu;
        $menu['sub'] = "List Changes";
        $data_view = $this->data_view;
        $descrip = ['description' => 'View Changes'];

        if ($request->ajax()) {
            $model = $this->mainService->getCRAll($request);
            return DataTables::eloquent($model)
                ->editColumn('id', function ($model) {
                    $view_nomor = "<span class='text-lg font-semibold " . ($model->status == "Open" ? "text-nomor-1" : "text-nomor-4") . "'>$model->nomor_changes</span>";

                    $view_title = "<br><span class='text-base font-semibold'>" . $model->title . "</span>";
                    $view_type = "<br><span class='text-base font-semibold'>" . ("-"  . (is_array($model->type) ? implode(', ', $model->type) : $model->type) .  "-") . "</span>";
                    $view_agenda = "<br><span class='text-base'>" . ($this->limitText($model->agenda, 50)) . "</span>";
                    if ($model->created_date) {
                        $created_date = str_replace('yang', '', $model->created_date->diffForHumans()) ?? '';
                        $view_created_date = "<br><i class='fas fa-clock " . ($model->status == 'Open' ? 'text-green-0' : '') . "'></i><span class='ml-2'>Dibuat $created_date</span>";
                    }
                    $view_last_updated_date = '';
                    if ($model->last_updated_date != null && $model->last_updated_date != '00') {
                        $view_last_updated_date = str_replace('yang', '', $model->last_updated_date->diffForHumans()) ?? '';
                        $view_last_updated_date = "<br><i class='fas fa-clock " . ($model->status == 'Open' ? 'text-green-0' : '') . "'></i><span class='ml-2'>Diupdate $view_last_updated_date</span>";
                    }

                    return "<div class='text-left'>$view_nomor $view_title $view_type $view_agenda" . "<div class='-mt-4 text-gray-500'>$view_last_updated_date" . "$view_created_date</div></div>";
                })
                ->editColumn('datetime_action', function ($model) {
                    return $model->datetime_action ? $model->datetime_action->locale('id')->isoFormat('dddd, D MMMM YYYY') : null;
                })
                ->editColumn('status_approval', function ($model) {
                    $is_submit = "<span class='block space-x-2 whitespace-nowrap'><i class='" . ($model->is_draft !== 0 ? 'far fa-square text-green-0' : ($model->is_draft !== 1 ? 'fas fa-square-check text-green-0' : 'fas fa-square-xmark text-red-0')) . "'></i><span>Submit</span></span>";
                    $approval_manager = "<span class='block space-x-2 whitespace-nowrap'><i class='" . ($model->status_approval1 === null ? 'far fa-square text-green-0' : (($model->status_approval1 === 1 && in_array($model->status_approval2, [null, 1]) || ($model->status_approval1 === 1 && in_array($model->status_approval2, [2]) && in_array($model->last_progress, ['Approval By Manager', 'Approval By GM']))) ? 'fas fa-square-check text-green-0' : ($model->status_approval1 === 1 && in_array($model->status_approval2, [2]) && in_array($model->last_progress, ['Edit']) ? 'far fa-square text-green-0' : ($model->status_approval1 === 2 && in_array($model->last_progress, ['Edit']) ? 'far fa-square text-green-0' : 'fas fa-square-xmark text-red-0')))) . "'></i><span>Manager</span></span>";
                    $approval_gm = $model->approval_level2_id ? "<span class='space-x-2 whitespace-nowrap'><i class='" . ($model->status_approval2 === null ? 'far fa-square text-green-0' : ($model->status_approval2 === 1 ? 'fas fa-square-check text-green-0' : ($model->status_approval2 === 2 && in_array($model->last_progress, ['Edit', 'Approval By Manager']) ? 'far fa-square text-green-0' : 'fas fa-square-xmark text-red-0'))) . "'></i><span>General Manager</span></span>" : '';
                    return "<div class='text-left'>" . $is_submit . $approval_manager . $approval_gm . "</div>";
                })
                ->editColumn('action', function ($model) {
                    return view('components.action-changes', compact('model'));
                })
                ->escapeColumns([])
                ->toJson();
        }
        $this->logActivity->createLog(['status' => 'success'] + $descrip);
        return view($this->view_prefix . 'index', compact('menu', 'data_view'));
    }

    function limitText($text, $limit)
    {
        if (mb_strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit) . '...';
        }
        return $text;
    }

    public function detail($id)
    {

        $menu = $this->menu;
        $menu['sub'] = "Detail Changes";
        $descrip = ['description' => 'Detail Changes'];

        $changes = $this->mainService->detailCR($id);
        $time = $this->mainService->timeProgress($id);
        if ($changes == null)
            throw new BadRequestException("Data tidak ditemukan!");
        try {
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view($this->view_prefix . 'detail', compact('menu', 'changes', 'time'));
        } catch (BadRequestException $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    function updateByColumn(Request $request, $id)
    {
        $descrip = ['description' => 'Submit Changes'];
        DB::beginTransaction();
        try {
            $this->mainService->updateByColumn($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return response()->json([
                'success' => true,
                'message' => 'Submit Changes sukses!',
                'data' => [
                    'route' => route($this->route_prefix . 'index')
                ]
            ], Response::HTTP_OK);
        } catch (BadRequestException $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        }
    }

    public function verifCR(Request $request, $id)
    {
        $descrip = ['description' => 'Verif Changes'];
        DB::beginTransaction();
        try {
            $this->mainService->updateProgress($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return response()->json([
                'success' => true,
                'message' => 'Update persetujuan sukses!',
                'data' => [
                    'route' => route($this->route_prefix . 'index')
                ]
            ], Response::HTTP_OK);
        } catch (BadRequestException $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        }
    }

    public function viewCreateOrEdit(Request $request)
    {
        $menu = $this->menu;
        $menu['sub'] = "Create Changes";
        $data_view = $this->data_view;
        $is_create = $request->id ? 0 : 1;

        $rolepermission = $this->mainService->getRolePermission();
        if (!in_array('Create Changes', $rolepermission->accessRights->pluck('access_name')->toArray()) && ($rolepermission->role->role_name ?? null) != 'Admin') :
            $message = "Anda tidak memiliki akses untuk create Changes!";
            return back()->with('error', $message);
        endif;

        if ($request->id && !in_array('Edit Changes', $rolepermission->accessRights->pluck('access_name')->toArray()) && ($rolepermission->role->role_name ?? null) != 'Admin') :
            $message = "Anda tidak memiliki akses untuk edit Changes!";
            return back()->with('error', $message);
        endif;

        $changes = ($request->id ?? null) ? ($this->mainService->detailCR($request->id) ?? null) : null;
        $ticket = ($request->ticket_id ?? $changes->ticket_reference_id ?? null) ? ($this->troubleTicketService->findById($request->ticket_id ?? $changes->ticket_reference_id) ?? null) : null;

        $last_is_not_approve = ($changes?->lastProgress ? in_array($changes->lastProgress->progress_type, ["Approval By Manager", "Approval By GM"]) && ($changes->lastProgress->approval ? $changes->lastProgress->approval->status_approval != 1 : false) : false);

        return view($this->view_prefix . 'create', compact('menu', 'data_view', 'changes', 'ticket', 'request', 'is_create', 'last_is_not_approve'));
    }

    public function viewUpdate($id)
    {

        $menu = $this->menu;
        $menu['sub'] = 'Update Ticket';
        $data_view = $this->data_view;

        $rolepermission = $this->mainService->getRolePermission();
        if (!in_array('Update Changes', $rolepermission->accessRights->pluck('access_name')->toArray()) && ($rolepermission->role->role_name ?? null) != 'Admin') :
            $message = "Anda tidak memiliki akses untuk update Changes!";
            return back()->with('error', $message);
        endif;

        $ticket = [];
        $changes = $this->mainService->detailCR($id);
        if (($changes->ticket_reference_id ?? null) != null) {
            $ticket = $this->troubleTicketService->findById($changes->ticket_reference_id) ?? null;
        }

        return view($this->view_prefix . 'update', compact('menu', 'data_view', 'changes', 'ticket'));
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function createChange(CRCreateRequest $request)
    {
        $descrip = ['description' => 'Create Changes'];
        DB::beginTransaction();
        try {
            $this->mainService->createChange($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Update data success' : 'Create Data Success',
                'data' => [
                    'route' => route($this->route_prefix . 'index')
                ]
            ], Response::HTTP_CREATED);
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            throw $ex;
        }
    }

    public function updateProgress(CRUpdateRequest $request, $id)
    {
        $descrip = ['description' => 'Update Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->updateProgress($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . 'index')->with('success', 'Berhasil mengupdate changes!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('error', 'Prosess Gagal!');
        }
    }


    public function export(Request $request, $type)
    {
        try {
            if (!in_array($type, ['pdf', 'excel', 'email'])) {
                throw new BadRequestException('Export type not supported');
            }

            $id = $request->input('id');

            if ($type === 'pdf') {
                return $this->_exportPDF($id);
            } else if ($type === 'excel') {
                return (new ChangeManageExport($this->mainService, $request))->download("change_manage" . ".$request->format");
            } else if ($type === 'email') {
                return $this->_testEmailCR($id);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            Log::error($ex->getTraceAsString());

            throw $ex;
        }
    }

    private function _exportPDF($id)
    {
        // TODO: Get data for export pdf by id
        $pdf = $this->mainService->exportPdfById($id);

        $attachments = [
        ];

        $pdfs = [];

        foreach ($pdf->attachments as $value) {
            $extension = pathinfo($value->name, PATHINFO_EXTENSION);
            // if (in_array(explode('.', $value->url ?? '')[1] ?? null, $format)) {
            if ($extension === 'pdf') {
                $pdfs[] = str_replace('public/', '', $value->url);
            } else {
                $attachments[] = str_replace('public/', '', $value->url);
            }
        }

        $type_convert = $pdf->type ? implode(', ', $pdf->type) : '';

        $pic_telkomsat = $pdf->pic_telkomsat != null ? $pdf->pic_telkomsat . " (Telkomsat)" : '';
        $pic_nontelkomsat = $pdf->pic_nontelkomsat != null ? $pdf->pic_nontelkomsat . " (Non Telkomsat)" : '';
        $pic = "$pic_telkomsat  $pic_nontelkomsat";
        $data = [
            'title' => $pdf->title,
            'table' => [
                'changeRequest' => $pdf->nomor_changes,
                'location' => $pdf->location_name,
                'picTelkomsat' => $pdf->pic_telkomsat,
                'picNonTelkomsat' => $pdf->pic_nontelkomsat,
                'typeOfMaintenance' => $type_convert,
                'priority' => $pdf->priority,
                'agenda' => $pdf->agenda,
                'dateTimeAction' => $pdf->datetime_action,
                'createdDate' => $pdf->created_date,
            ],
            'content' => $pdf->content,
            'attachments' => $attachments ?? [],
            'status_approval1' => $pdf->status_approval1 ?? null,
            'status_approval2' => $pdf->status_approval2 ?? null,
            'engineer' => $pdf->engineer_name ?? null,
            'inisial_engineer' => $pdf?->engineer?->karyawan?->inisial,
            'jabatan_engineer' => $pdf?->engineer?->karyawan?->jabatan?->nama ? $pdf?->engineer?->karyawan?->jabatan?->nama : 'Engineer',
            'manager' => $pdf->approval1_name ?? null,
            'inisial_manager' => $pdf?->approval1?->karyawan?->inisial,
            'jabatan_manager' => $pdf?->approval1?->karyawan?->jabatan?->nama ? $pdf?->approval1?->karyawan?->jabatan?->nama : $pdf?->jabatan_manager,
            'gm' => $pdf->approval2_name ?? null,
            'inisial_gm' => $pdf?->approval2?->karyawan?->inisial,
            'jabatan_gm' => $pdf?->approval2?->karyawan?->jabatan?->nama ? $pdf?->approval2?->karyawan?->jabatan?->nama : 'GM Information Technology',
        ];


        $pdf_res = PDF::loadview('pdfs.change-request-rev', $data);
        $pdf_res->setPaper('A4', 'portrait');
        $pdf_res_path = public_path('pdf_res.pdf');
        $pdf_res->save($pdf_res_path);

        $oMerger = PDFMerger::init();

        $oMerger->addPDF($pdf_res_path, 'all');

        foreach ($pdfs as $item_pdf) {

            $filePath = str_replace('\\', '/', public_path('storage/' . $item_pdf));

            if (file_exists($filePath)) {
                $oMerger->addPDF($filePath, 'all');
            }
        }
        $oMerger->merge();
        $oMerger->save($pdf->nomor_changes . ".pdf", "download");
    }

    private function _testEmailCR($id)
    {
        // TODO: Get data for email by id

        $pdf = $this->mainService->exportPdfById($id);
        $email = env('EMAIL_TEST');

        $attachments = [
        ];

        $pic_telkomsat = $pdf->pic_metrasat != null ? $pdf->pic_metrasat . " (Telkomsat)" : '';
        $pic_non_telkomsat = $pdf->pic_non_metrasat != null ? $pdf->pic_non_metrasat . " (Non Telkomsat)" : '';
        $pic = "$pic_telkomsat  $pic_non_telkomsat";


        $data = [
            'subject' => $pdf->title,
            'data' => [
                'title' => "$pdf->no - Change Request",
                'table' => [
                    'changeRequest' => $pdf->no,
                    'date' => Carbon::parse($pdf->created)->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                    'time' => Carbon::parse($pdf->created)->locale('id')->isoFormat('H:m'),
                    'location' => $pdf->location,
                    'pic' => $pic,
                    'typeOfMaintenance' => $pdf->type_of_maintenance,
                    'agenda' => $pdf->agenda,
                    'dateTimeAction' => $pdf->date_time
                ],
                'content' => $pdf->content,
                'attachments' => $attachments ?? [],
                'engineer' => $pdf->engineer_name,
                'manager' => $pdf->manager_name,
                'gm' => $pdf->upManager_name,
            ]
        ];

        $data = [
            'title' => $pdf->title,
            'table' => [
                'changeRequest' => $pdf->no,
                'date' => Carbon::parse($pdf->created)->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'time' => Carbon::parse($pdf->created)->locale('id')->isoFormat('H:m'),
                'location' => $pdf->location,
                'pic' => $pic,
                'typeOfMaintenance' => $pdf->type_of_maintenance,
                'agenda' => $pdf->agenda,
                'dateTimeAction' => $pdf->date_time
            ],
            'content' => $pdf->content,
            'attachments' => $attachments ?? [],
            'engineer' => $pdf->engineer_name,
            'manager' => $pdf->manager_name,
            'gm' => $pdf->upManager_name,
        ];


        dispatch(new EmailChangeRequest($email, $data));
    }

    public function comment(ChangeManage $change)
    {
        $menu = $this->menu;
        $menu['sub'] = "List Changes";
        $data = [
            'menu' => $menu,
            'change' => $change
        ];


        return view($this->view_prefix . 'comment', $data);
    }

    public function createComment(Request $request, ChangeManage $change)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['user_id'] = auth()->id();

            $change->comments()->create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comment sukses!',
                'data' => null
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e, __METHOD__);

            DB::rollBack();

            throw $e;
        }
    }

    public function getAllComment(Request $request, ChangeManage $change)
    {
        try {
            $change = $change->with(['comments.user'])->find($change->id);

            $comments = $change->comments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'comment' => $item->comment,
                    'created_at' => $item->created_at->diffForHumans(),
                    'creator' => $item->user->full_name,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Get All Comment Success',
                'data' => $comments
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    public function deleteComment($id)
    {
        $descrip = ['description' => 'Delete Comment'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteComment($id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return response()->json([
                'success' => true,
                'message' => 'Delete comment sukses!',
                'data' => [
                    'route' => route($this->route_prefix . 'index')
                ]
            ], Response::HTTP_OK);
        } catch (BadRequestException $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        }
    }

    public function deleteChanges(Request $request, $id)
    {

        $descrip = ['description' => 'Delete Changes'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteChanges($request, $id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Changes berhasil dihapus!',
            ], Response::HTTP_OK);
        } catch (BadRequestException $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $e] + $descrip);
            throw $e;
        }
    }
}
