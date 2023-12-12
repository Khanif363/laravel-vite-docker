<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Exports\DepartmentExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Services\Department\DepartmentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepartmentController extends Controller
{
    private $mainService;
    private $menu;
    private $route_prefix = 'departments.';
    private $view_prefix = 'pages.department.';
    private $logActivity;

    public function __construct(DepartmentService $mainService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama'     => 'Department',
            'opsi'      => [
                [
                    'nama' => 'List Department',
                    'link' => '/departments'
                ],
                [
                    'nama' => 'Create Department',
                    'link' => '/departments/create'
                ]
            ]
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List Department";
        $descrip = ['description'=> 'View Department'];

        try {
            $data = $this->mainService->getDepartmentAll();
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
        $menu['sub'] = "Create Department";

        return view($this->view_prefix . "create", compact('menu'));
    }

    public function viewUpdate($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update Department";
        $data = $this->mainService->getById($id);

        return view($this->view_prefix . "update", compact('menu', 'data'));
    }

    public function export(Request $request)
    {
        return (new DepartmentExport($this->mainService))->download("departments" . ".$request->format");
    }

    public function createDepartment(DepartmentRequest $request)
    {
        $descrip = ['description'=> 'Create Department'];
        DB::beginTransaction();
        try {
            $this->mainService->createDepartment($request);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix."index")->with('success', 'Depertment Berhasil dibuat');
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

    public function updateDepartment(DepartmentRequest $request, $id)
    {
        $descrip = ['description'=> 'Update Department'];
        DB::beginTransaction();
        try {
            $this->mainService->updateDepartment($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Depertment Berhasil diupdate');
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

    public function deleteDepartment($id)
    {
        $descrip = ['description'=> 'Delete Department'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteDepartment($id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Device berhasil dihapus!');
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

    public function getDepartmentByTipe(Request $request)
    {
        try {
            $type = $request->input('type');
            // TODO: Get Data
            $data = $this->mainService->getDepartmentByTipe($type);

            return response()->json([
                'success' => true,
                'message' => 'Get Department Success',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }
}
