<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Services\Service\ServiceService;
use App\Services\Department\DepartmentService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ServiceController extends Controller
{
    private $mainService;
    private $menu;
    private $route_prefix = 'services.';
    private $view_prefix = 'pages.service.';
    private $data_view;
    private $logActivity;

    public function __construct(ServiceService $mainService, DepartmentService $departmentService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;

        $this->menu = [
            'utama'     => 'Service',
            'opsi'      => [
                [
                    'nama' => 'List Service',
                    'link' => '/services'
                ],
                [
                    'nama' => 'Create Service',
                    'link' => '/services/create'
                ]
            ]
        ];

        $categories = [
            'COMIT IT Tools',
            'COMIT Fulfillment',
            'COMIT Assurance',
            'COMIT Application',
            'COMIT SOC'
        ];


        $this->data_view = [
            'departments_semicore'  =>  $departmentService->getDepartmentSemiCore(),
            'categories' => $categories
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List Service";
        $descrip = ['description'=> 'View Service'];

        try {
            $data = $this->mainService->getServiceAll();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view($this->view_prefix.'index', compact('menu', 'data'));
        } catch (Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    public function viewCreate()
    {
        $menu = $this->menu;
        $menu['sub'] = "Create Service";
        $data_view = $this->data_view;

        return view($this->view_prefix.'create', compact('menu', 'data_view'));
    }

    public function viewupdate($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update Service";
        $data = $this->mainService->getById($id);
        $data_view = $this->data_view;

        return view($this->view_prefix . 'update', compact('data', 'menu', 'data_view'));
    }

    public function export(Request $request)
    {
        return (new ServiceExport($this->mainService))->download("services" . ".$request->format");
    }

    public function createService(ServiceRequest $request)
    {
        $descrip = ['description'=> 'Create Service'];
        DB::beginTransaction();
        try {
            $this->mainService->createService($request);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Service berhasil dibuat!');
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

    public function updateService(ServiceRequest $request, $id)
    {
        $descrip = ['description'=> 'Update Service'];
        DB::beginTransaction();
        try {
            $this->mainService->updateService($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Service berhasil diupdate!');
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

    public function deleteService($id)
    {
        $descrip = ['description'=> 'Delete Service'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteService($id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Service berhasil didelete!');
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
