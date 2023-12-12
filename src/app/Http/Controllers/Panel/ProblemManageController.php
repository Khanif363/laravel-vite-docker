<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\ProblemManageExport;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CreateProblemManageRequest;
use App\Services\ProblemManage\ProblemManageService;
use App\Services\TroubleTicket\TroubleTicketService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProblemManageController extends Controller
{
    private $mainService;
    private $ticketService;
    private $menu;
    private $route_prefix = 'problem-managements.';
    private $view_prefix = 'pages.problem-management.';
    private $logActivity;

    public function __construct(ProblemManageService $mainService, TroubleTicketService $ticketService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->ticketService = $ticketService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama'     => "Root Cause Analysis",
            'opsi'      => [
                [
                    'nama' => 'List RCA',
                    'link' => '/problem-managements'
                ],
                [
                    'nama' => 'Create RCA',
                    'link' => '/problem-managements/create'
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $menu = $this->menu;
        $menu['sub'] = "List RCA";
        $descrip = ['description' => 'View RCA'];

        if ($request->ajax()) {
            $model = $this->mainService->getProblemManageAll($request);
            return DataTables::eloquent($model)
                ->editColumn('action', function ($model) {
                    return view('components.action-rca', compact('model'));
                })
                ->orderColumn('nomor_problem', function ($query, $order = 'desc') {
                    $query->orderBy('id', $order);
                })
                ->toJson();
        }
        $this->logActivity->createLog(['status' => 'success'] + $descrip);
        return view($this->view_prefix . 'index', compact('menu'));
    }

    public function detail($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "List RCA";
        $descrip = ['description' => 'Detail RCA'];

        try {
            $problem = $this->mainService->detailProblem($id);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view($this->view_prefix . 'detail', compact('menu', 'problem'));
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    public function viewCreate(Request $request)
    {
        $menu = $this->menu;
        $menu['sub'] = "Create RCA";

        $ticket_id = $request->input('ticket_id');
        $ticket = [];

        if (!empty($ticket_id))
            $ticket = $this->ticketService->findById($ticket_id);

        return view($this->view_prefix . 'create', compact('menu', 'ticket'));
    }

    public function viewUpdate(Request $request, $id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update RCA";

        $problem = $this->mainService->findById($id);
        if (isset($problem->troubleTicket->id)) :
            $ticket = $this->ticketService->findById($problem->troubleTicket->id);
        endif;
        $data['ticket'] = $ticket ?? (object)[];
        $data['problem'] = $problem;
        return view($this->view_prefix . 'update', compact('menu', 'data'));
    }

    public function export(Request $request)
    {
        return (new ProblemManageExport($this->mainService, $request))->download("problem_manage" . ".$request->format");
    }

    public function createProblem(CreateProblemManageRequest $request)
    {
        $descrip = ['description' => 'Create RCA'];
        DB::beginTransaction();
        try {
            $this->mainService->createProblem($request);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . 'index')->with('success', 'Berhasil membuat RCA!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('Prosess Gagal!');
        }
    }

    public function updateProblem(Request $request, $id)
    {
        $descrip = ['description' => 'Update RCA'];
        DB::beginTransaction();
        try {
            $this->mainService->updateProblem($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . 'index')->with('success', 'RCA berhasil diupdate!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('Prosess Gagal!');
        }
    }

    public function verifProblem(Request $request, $id)
    {
        $descrip = ['description' => 'Verif RCA'];
        DB::beginTransaction();
        try {
            $this->mainService->verifProblem($request, $id);
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
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            throw $e;
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            throw $e;
        }
    }
}
