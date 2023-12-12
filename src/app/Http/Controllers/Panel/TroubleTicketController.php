<?php

namespace App\Http\Controllers\Panel;

use stdClass;
use Exception;
use App\Helpers\SelectView;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Exports\TicketExport;
use App\Mail\EmailNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Device\DeviceService;
use App\Services\Service\ServiceService;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Services\Location\LocationService;
use App\Services\Department\DepartmentService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ChangeManage\ChangeManageService;
use App\Services\ResumeClosing\ResumeClosingService;
use App\Services\TroubleTicket\TroubleTicketService;
use App\Services\User3Easy\User3EasyService;
use App\Services\UserManagement\UserManagementService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TroubleTicketController extends Controller
{
    private $mainService;
    private $menu;
    private $data_view;
    private $logActivity;
    private $changeManageService;
    private $user3EasyService;

    public function __construct(TroubleTicketService $mainService, DepartmentService $departmentService, ResumeClosingService $resumeClosingService, ServiceService $serviceService, DeviceService $deviceService, UserManagementService $userManagementService, LocationService $locationService, LogActivity $logActivity, ChangeManageService $changeManageService, User3EasyService $user3EasyService)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
        $this->changeManageService = $changeManageService;
        $this->user3EasyService = $user3EasyService;
        $this->menu = [
            'utama' => 'Ticket',
            'opsi' => [
                [
                    'nama' => 'List Ticket',
                    'link' => '/tickets'
                ],
                [
                    'nama' => 'Create Ticket',
                    'link' => '/tickets/create'
                ]
            ]
        ];

        $types = [
            'Fulfillment',
            'Incident TTR',
            'Incident Non TTR',
            'Service Request',
            // 'Enhancement',
            // 'Changes',
        ];

        $categories = [
            'COMIT IT Tools',
            'COMIT Fulfillment',
            'COMIT Assurance',
            'COMIT Application',
            'COMIT SOC'
        ];

        $problem_types = [
            'Non Gamas',
            'Gamas Non Impact',
            'Gamas Impact'
        ];

        $sources_info_troubles = [
            'Telp',
            'WhatsApp Personal',
            'WhatsApp Group',
            'Email',
            'Nota Dinas',
            'Memo',
            'Work Order',
        ];

        $details_info = [
            'PIC',
            'NON PIC',
            'After Sales',
            'AM',
            'Teknisi'
        ];

        $update_types = [
            'Diagnose',
            'Dispatch',
            'Engineer Assignment',
            'Engineer Troubleshoot',
            'Pending',
            'Technical Close',
            'Monitoring',
            'Closed'
        ];

        $this->data_view = [
            'departments'                   => $departmentService->getDepartmentActive(),
            'departments_core'             => $departmentService->getDepartmentCore(),
            'departments_semicore'         => $departmentService->getDepartmentSemiCore(),
            'resumes'                       => $resumeClosingService->getResumeClosingAll(),
            'update_types'                  => SelectView::update_type(),
            'pendings'                      => SelectView::pending(),
            'services'                      => $serviceService->getServiceAll(),
            'devices'                       => $deviceService->getDeviceAll(),
            'engineers'                     => $userManagementService->getEngineerManager(),
            'users'                         => $userManagementService->getUserAll(),
            'locations'                     => $locationService->getLocationAll(),
            'types'                         => $types,
            'categories'                    => $categories,
            'problem_types'                 => $problem_types,
            'sources_info_troubles'         => $sources_info_troubles,
            'details_info'                  => $details_info,
            'mantools_datacoms'             => $mainService->getMantoolsDatacom(),
            'update_types'                  => $update_types
        ];
    }

    // View List Ticket
    public function index(Request $request)
    {
        $status = $request['status'];
        $department = $request['department'];

        $menu = $this->menu;
        $menu['sub'] = 'List Ticket';
        $descrip = ['description' => 'View Ticket'];

        $data_view = $this->data_view;

        if ($request->ajax()) {
            // Get All Ticket for Response Ajax
            $model = $this->mainService->getTicketAll($request);
            return DataTables::eloquent($model)
                ->editColumn('nomor_ticket', function ($model) {
                    $view_info_resume = '';
                    if (($model->time_diff_now ?? null) && ($model->department_name ?? null) && $model->status != 'Closed' && $model->technical_closed_date === null && (!in_array($model->resume_gm, [1, 2]) || $model->resume_cto == 0) && $model->type === 'Incident TTR') {
                        $view_resume = $model->resume_gm == 0 && $model->time_diff_now >= '01:00' && $model->time_diff_now < '24:00' && in_array(($model->problem_type ?? null), ['Gamas Non Impact', 'Gamas Impact']) && auth()->user()->role_id == 3 ? 'Resume GM' : ($model->resume_cto == 0 && $model->time_diff_now >= '24:00' && auth()->user()->role_id == 2 ? 'Resume CTO' : null);
                        if ($view_resume != null)
                            $view_info_resume = "<br><i class='fas fa-circle-info text-red-0'></i><span class='ml-2 text-sm font-medium text-red-0'>" . $view_resume . "</span>";
                    }
                    $view_nomor = "<span class='text-lg font-semibold " . ($model->status !== 'Waiting Close' ? ($model->time_diff_now <= '02:00' && $model->status != 'Closed' ? 'text-nomor-1' : ($model->time_diff_now > '02:00' && $model->time_diff_now <= '04:00' && $model->status != 'Closed' ? 'text-nomor-2' : ($model->time_diff_now > '04:00' && $model->time_diff_now <= '06:00' && $model->status != 'Closed' ? 'text-nomor-3' : ($model->time_diff_now > '06:00' && $model->time_diff_now <= '08:00' && $model->status != 'Closed' ? 'text-nomor-4' : ($model->status != 'Closed' ? 'text-nomor-5' : ''))))) : '') . "'>$model->nomor_ticket</span>$view_info_resume";

                    if ($model->created_date) {
                        $created_date = str_replace('yang', '', $model->created_date->diffForHumans()) ?? '';
                        $view_created_date = "<br><i class='fas fa-clock " . ($model->status == 'Open' ? 'text-green-0' : ($model->status == 'Pending' ? 'text-yellow-0' : '')) . "'></i><span class='ml-2'>Dibuat $created_date</span>";
                    }
                    $view_last_updated_date = '';
                    if ($model->last_updated_date != null && $model->last_updated_date != '00') {
                        $view_last_updated_date = str_replace('yang', '', $model->last_updated_date->diffForHumans()) ?? '';
                        $view_last_updated_date = "<br><i class='fas fa-clock " . ($model->status == 'Open' ? 'text-green-0' : ($model->status == 'Pending' ? 'text-yellow-0' : '')) . "'></i><span class='ml-2'>Diupdate $view_last_updated_date</span>";
                    }

                    return "<div class='text-left whitespace-nowrap'>$view_nomor" . "<div class='-mt-4 text-gray-500'>$view_last_updated_date" . "$view_created_date</div></div>";
                })
                ->editColumn('action', function ($model) {
                    return view('components.action-ticket', compact('model'));
                })
                ->rawColumns(['nomor_ticket'])
                ->orderColumn('nomor_ticket', function ($query, $order = 'desc') {
                    $query->orderBy('id', $order);
                })
                ->escapeColumns([])
                ->toJson();
        }
        $this->logActivity->createLog(['status' => 'success'] + $descrip);
        return view('pages.trouble-ticket.index', compact('menu', 'data_view', 'status', 'department'));
    }

    // View Detail Ticket
    public function detail($id)
    {
        $menu = $this->menu;
        $menu['sub'] = 'Detail Ticket';
        $descrip = ['description' => 'Detail Ticket'];
        DB::beginTransaction();
        try {
            $ticket = $this->mainService->detailTicket($id);
            $time = $this->mainService->timeProgress($id);

            if ($ticket == null)
                throw new BadRequestException("Data tidak ditemukan!");

            try {
                $this->logActivity->createLog(['status' => 'success'] + $descrip);
                return view('pages.trouble-ticket.detail', compact('menu', 'ticket', 'time'));
            } catch (BadRequestException $ex) {
                DB::rollBack();
                $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
                return back()->with('error', $ex->getMessage());
            } catch (\Exception $ex) {
                DB::rollBack();
                $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
                return back()->with('Tidak dapat ditampilkan');
            }
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    // View for Create Form
    public function viewCreate(Request $request)
    {
        $menu = $this->menu;
        $menu['sub'] = 'Create Ticket';
        $user3Easy = $this->user3EasyService->getUserAll();

        $rolepermission = $this->mainService->getRolePermission();
        if ((in_array('Create Ticket', $rolepermission->accessRights->pluck('access_name')->toArray()) || in_array('Create Changes', $rolepermission->accessRights->pluck('access_name')->toArray())) || ($rolepermission->role->role_name ?? null) == 'Admin') :
            $ticket = $request->id ? ($this->mainService->findById($request->id) ?? null) : null;

            $data_view = $this->data_view;

        else :
            $message = "Anda tidak memiliki akses untuk create Ticket!";
            return back()->with('error', $message);
        endif;
        return view('pages.trouble-ticket.create', compact('menu', 'data_view', 'ticket', 'user3Easy'));
    }

    // View for Update form
    public function viewUpdate($id)
    {

        $menu = $this->menu;
        $menu['sub'] = 'Update Ticket';

        $rolepermission = $this->mainService->getRolePermission();
        if (in_array('Update Ticket', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) || ($rolepermission->role->role_name ?? null) == 'Admin') :
            $ticket = $this->mainService->findById($id);
            $last_department = $this->mainService->lastDepartment($id);
            $data_view = $this->data_view;
        else :
            $message = "Anda tidak memiliki akses untuk update Ticket!";
            return back()->with('error', $message);
        endif;
        return view('pages.trouble-ticket.update', compact('menu', 'data_view', 'ticket', 'last_department'));
    }

    // Export Execution
    public function export(Request $request)
    {
        // Return Export class
        return (new TicketExport($this->mainService, $request))->download("tickets_" . $request->status . ".$request->format");
    }

    // Create Ticket Process
    public function createTicket(CreateTicketRequest $request)
    {
        $descrip = ['description' => 'Create Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->createTicket($request);
            DB::commit();
            $message = 'Berhasil membuat ticket!';
            if ($request->step == 2)
                $message = 'Berhasil melengkapi data ticket!';
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route('tickets.index')->with('success', $message);
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('error', 'Process Gagal!');
        }
    }

    // Update Ticket Process
    public function updateProgress(UpdateTicketRequest $request, $id)
    {
        $descrip = ['description' => 'Update Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->updateProgress($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route('tickets.index')->with('success', 'Berhasil mengupdate ticket!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return back()->with('error', 'Process Gagal!');
        }
    }

    // Edit Ticket Progress
    public function editProgress(Request $request, $id)
    {
        $descrip = ['description' => 'Edit Progress Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->editProgress($request, $id);
            $ticket_id = $this->mainService->findTicketProgress($id)->trouble_ticket_id;
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengedit progress!',
                'data' => [
                    'route' => route('tickets.detail', $ticket_id)
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

    // Edit by column Ticket
    public function editCreate(Request $request, $id)
    {
        $descrip = ['description' => 'Edit Create Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->editCreate($request, $id);
            $ticket_id = $this->mainService->findById($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengedit progress!',
                'data' => [
                    'route' => route('tickets.detail', $ticket_id)
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

    // Get Last department of Ticket
    public function lastDepartment($id)
    {
        return $this->mainService->lastDepartment($id);
    }

    // Get alert to show after login
    public function getAlert(Request $request)
    {
        $data = $this->mainService->getAlert($request);

        return response()->json([
            'success' => true,
            'message' => 'Get Alert Success',
            'data' => $data
        ], Response::HTTP_OK);
    }

    // Resume Ticket (not implemented)
    public function viewSendResume($id)
    {
        $menu = $this->menu;
        $menu['sub'] = 'Send Resume Ticket';

        $ticket = $this->mainService->findById($id);

        return view('pages.trouble-ticket.send-resume', compact('ticket', 'menu'));
    }

    // Send Resume Process
    public function sendResume(Request $request, $id)
    {
        $descrip = ['description' => 'Send Resume Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->sendEmail($request, $id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengirim resume!',
                'data' => [
                    'route' => route('tickets.index')
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

    // Push notif ticket (not implemented)
    public function pushNotif(Request $request, $id)
    {
        $descrip = ['description' => 'Push Notification'];
        DB::beginTransaction();
        try {
            $this->mainService->pushNotif($request, $id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengirim notifikasi!',
                'data' => [
                    'route' => route('tickets.index')
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

    // Read notif process
    public function readNotif(Request $request, $id)
    {
        $descrip = ['description' => 'Read Notif'];
        DB::beginTransaction();
        try {
            $this->mainService->readNotif($request, $id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses pin read!',
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

    // Get total ticket for dashboard
    public function getTotalTicket(Request $request)
    {
        try {
            $department_id = $request->input('department_id');

            $ticket = $this->mainService->getTotalTicket($department_id);
            $data = [
                'open' => $ticket[0]->open_count ?? 0,
                'pending' => $ticket[0]->pending_count ?? 0,
                'closed' => $ticket[0]->closed_count ?? 0,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get Total Success',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // Get MTTR (Resolve) for dashboard
    public function getMTTR(Request $request)
    {
        try {
            $department_id = $request->input('department_id');

            $mttr = $this->mainService->getMTTR($department_id);

            return response()->json([
                'success' => true,
                'message' => 'Get MTTR Success',
                'data' => $mttr
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // Get MTTA (Acknowledge) for dashboard
    public function getMTTA()
    {
        try {
            $mtta = $this->mainService->getMTTA();

            return response()->json([
                'success' => true,
                'message' => 'Get MTTA Success',
                'data' => $mtta
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // Get top service by ticket
    public function getTopTicket(Request $request)
    {
        try {
            $ticket = $this->mainService->getTopTicket();
            $data = [
                [
                    'layanan' => $ticket[0]->name ?? 0,
                    'total' => $ticket[0]->service_top3 ?? 0,
                ],
                [
                    'layanan' => $ticket[1]->name  ?? 0,
                    'total' => $ticket[1]->service_top3  ?? 0,
                ],
                [
                    'layanan' => $ticket[2]->name  ?? 0,
                    'total' => $ticket[2]->service_top3  ?? 0,
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get Top Ticket Success',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // get ticket open more than 3 days
    public function getTotalOpenTicketMoreThan3Days(Request $request)
    {
        try {
            $ticket = $this->mainService->getOpenMore3Days();
            $data = [
                'total' => $ticket[0]->open_more3days
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get Total Ticket More Than 3 Days Success',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // Get score of customer
    public function getTotalSkorKepuasanPelanggan(Request $request)
    {
        try {
            $rate = $this->mainService->getRate();
            $data = [
                'total' => $rate[0]->mean_rate
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get Total Skor Kepuasan Pelanggan Success',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    // Delete the ticket
    public function deleteTicket(Request $request, $id)
    {

        $descrip = ['description' => 'Delete Ticket'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteTicket($request, $id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Ticket berhasil dihapus!',
                'data' => [
                    'route' => route('tickets.index')
                ]
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

    // Preview email ticket (development)
    public function previewEmail($id)
    {
        $ticket = $this->mainService->findById($id);
        Log::info($ticket);
        $handleTime = '';
        foreach ($ticket->troubleTicketProgress as $progress) {
            if ($progress->update_type == 'Technical Close') {
                $handlingDuration = date_diff($progress->inputed_date, $ticket->created_date);
                $handleTime = $handlingDuration ? ($handlingDuration->d != 0 ? "$handlingDuration->d Hari, " : '') . ($handlingDuration->h != 0 ? "$handlingDuration->h Jam, " : '') . ($handlingDuration->i != 0 ? "$handlingDuration->i Menit, " : '') . ($handlingDuration->s != 0 ? "$handlingDuration->s Detik" : '') : '';
            }
        }
        $data_email = [
            'subject' => $ticket->subject,
            'data' => [
                'type' => 'Tess Update',
                'title' => "$ticket->nomor_ticket - $ticket->type",
                'ticket'      => $ticket->nomor_ticket,
                'department'      => $ticket->department->name ?? null,
                'service'      => $ticket->service->name ?? null,
                'device'      => $ticket->device->name ?? null,
                'dataTicket' => $ticket,
                'handlingDuration' => $handleTime,
                'progressTicket' => $ticket->troubleTicketProgress,
                'dear' => 'customer'
            ],
            'view'  => 'emails.trouble-ticket-non-customer' ?? null
        ];
        $mail = new EmailNotification($data_email);
        $preview = $mail->render();
        return $preview;
    }

    // Preview pdf ticket
    public function previewPdf($id)
    {
        $pdf = $this->changeManageService->exportPdfById($id);

        $tagsDanTeks = preg_split('/(<[^>]*>)/', $pdf->content, -1, PREG_SPLIT_DELIM_CAPTURE);
        $attachments = [
        ];

        $pdfs = [];

        foreach ($pdf->attachments as $value) {
            $extension = pathinfo($value->name, PATHINFO_EXTENSION);
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
        $pdf_res->render();

        return $pdf_res->stream();
    }
}
