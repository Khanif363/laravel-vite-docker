<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Exports\DeviceExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequest;
use App\Services\Device\DeviceService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DeviceController extends Controller
{
    private $mainService;
    private $menu;
    private $route_prefix = 'devices.';
    private $view_prefix = 'pages.device.';
    private $logActivity;

    public function __construct(DeviceService $mainService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama' => 'Device',
            'opsi'  => [
                [
                    'nama' => 'List Device',
                    'link' => '/devices'
                ],
                [
                    'nama' => 'Create Device',
                    'link' => '/devices/create'
                ]
            ]
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List Device";
        $descrip = ['description'=> 'View Device'];

        try {
            $data = $this->mainService->getDeviceAll();
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
        $menu['sub'] = "Create Device";

        return view($this->view_prefix . 'create', compact('menu'));
    }

    public function viewUpdate($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update Device";
        $data = $this->mainService->findById($id);

        return view($this->view_prefix . 'update', compact('menu', 'data'));
    }

    public function export(Request $request)
    {
        return (new DeviceExport($this->mainService))->download("devices" . ".$request->format");
    }

    public function createDevice(DeviceRequest $request)
    {
        $descrip = ['description'=> 'Create Device'];
        DB::beginTransaction();
        try {
            $this->mainService->createDevice($request);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Device berhasil dibuat!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('Prosess Gagal!');
        }
    }

    public function updateDevice(DeviceRequest $request, $id)
    {
        $descrip = ['description'=> 'Update Device'];
        DB::beginTransaction();
        try {
            $data = $this->mainService->updateDevice($request, $id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Device berhasil diupdate!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('Prosess Gagal!');
        }
    }

    public function deleteDevice($id)
    {
        $descrip = ['description'=> 'Delete Device'];
        DB::beginTransaction();
        try {
            $this->mainService->deleteDevice($id);
            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return redirect()->route($this->route_prefix . "index")->with('success', 'Device berhasil dihapus!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            Log::error($ex->getMessage());
            return back()->with('Prosess Gagal!');
        }
    }
}
