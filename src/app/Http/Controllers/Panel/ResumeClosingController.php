<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\ResumeClosingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeClosingRequest;
use App\Services\Department\DepartmentService;
use App\Services\ResumeClosing\ResumeClosingService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ResumeClosingController extends Controller
{
    private $mainService;
    private $menu;
    private $route_prefix = 'resumes.';
    private $view_prefix = 'pages.resume-closing.';
    private $data_view;
    private $logActivity;

    public function __construct(ResumeClosingService $mainService, DepartmentService $departmentService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama'     => "Resume Closing",
            'opsi'      => [
                [
                    'nama' => 'List Resume Closing',
                    'link' => '/resumes'
                ],
                [
                    'nama' => 'Create Resume Closing',
                    'link' => '/resumes/create'
                ]
            ]
        ];

        $this->data_view = [
            'departments'           => $departmentService->getDepartmentActive(),
            'departments_semicore'  => $departmentService->getDepartmentSemiCore()
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List Resume Closing";
        $descrip = ['description'=> 'View Resume Closing'];

        try {
            $data = $this->mainService->getResumeClosingAll();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view($this->view_prefix . "index", compact('menu', 'data'));
        } catch (Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    public function viewCreate()
    {
        $menu = $this->menu;
        $menu['sub'] = "Create Resume Closing";

        $data_view =  $this->data_view;

        return view($this->view_prefix . 'create', compact('menu', 'data_view'));
    }

    public function viewUpdate($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update Resume Closing";

        $data = $this->mainService->getById($id);
        $data_view =  $this->data_view;

        return view($this->view_prefix . 'update', compact('menu', 'data_view'));
    }

    public function export(Request $request)
    {
        return (new ResumeClosingExport($this->mainService))->download("resumes" . ".$request->format");
    }

    public function createResume(ResumeClosingRequest $request)
    {
        $descrip = ['description'=> 'Create Resume Closing'];
        DB::beginTransaction();
        try {
            $this->mainService->createResumeClosing($request);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . 'index')->with('success', 'RCA berhasil dibuat!');
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

    public function updateResume(Request $request, $id)
    {
        $descrip = ['description'=> 'Update Resume Closing'];
        DB::beginTransaction();
        try {
            $this->mainService->updateResumeClosing($request, $id);
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

    public function deleteResume($id)
    {
        $descrip = ['description'=> 'Delete Resume Closing'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteResumeClosing($id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . 'index')->with('success', 'RCA berhasil dihapus!');
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
}
