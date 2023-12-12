<?php

namespace App\Http\Controllers\Panel;

use App\Exports\LocationExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Services\Location\LocationService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LocationController extends Controller
{
    private $mainService;
    private $menu;
    private $route_prefix = 'locations.';
    private $view_prefix = 'pages.location.';

    public function __construct(LocationService $mainService)
    {
        $this->mainService = $mainService;
        $this->menu = [
            'utama'     => 'Location',
            'opsi'      => [
                [
                    'nama' => 'List Location',
                    'link' => '/locations'
                ],
                [
                    'nama' => 'Create Location',
                    'link' => '/locations/create'
                ]
            ]
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List Location";

        $data = $this->mainService->getLocationAll();

        return view($this->view_prefix . 'index', compact('menu', 'data'));
    }

    public function export(Request $request)
    {
        return (new LocationExport($this->mainService))->download("locations" . ".$request->format");
    }

    public function viewCreate()
    {
        $menu = $this->menu;
        $menu['sub'] = "Create Location";

        return view($this->view_prefix . "create", compact('menu'));
    }

    public function viewUpdate($id)
    {
        $menu = $this->menu;
        $menu['sub'] = "Update Location";
        $data = $this->mainService->getById($id);

        return view($this->view_prefix . "update", compact('menu', 'data'));
    }

    public function createLocation(LocationRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->mainService->createLocation($request->validated());
            DB::commit();
            return redirect()->route($this->route_prefix . "index")->with('success', 'Lokasi Berhasil ditambahkan');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            return back()->with('error', $ex->getMessage());
        }
    }

    public function updateLocation(LocationRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->mainService->updateLocation($request->validated(), $id);
            DB::commit();
            return redirect()->route($this->route_prefix . "index")->with('success', 'Lokasi Berhasil diupdate!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteLocation($id)
    {
        DB::beginTransaction();
        try {
            $this->mainService->deleteLocation($id);
            DB::commit();
            return redirect()->route($this->route_prefix . "index")->with('success', 'Lokasi Berhasil dihapus!');
        } catch (BadRequestException $ex) {
            DB::rollBack();
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            return back()->with('error', $ex->getMessage());
        }
    }
}
