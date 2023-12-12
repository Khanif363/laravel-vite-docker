<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogService;

class CatalogController extends Controller
{
    private $mainService;
    private $logActivity;

    public function __construct(CatalogService $mainService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
    }

    public function index(Request $request)
    {
        $menu['utama'] = "Katalog";
        $descrip = ['description'=> 'View Katalog'];
        try {
            $data = $this->mainService->getCatalogAll($request);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view('pages.catalog.index', compact('menu', 'data'));
        } catch (Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }
}
