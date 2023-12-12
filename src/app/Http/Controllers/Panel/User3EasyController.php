<?php

namespace App\Http\Controllers\Panel;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\User3Easy\User3EasyService;

class User3EasyController extends Controller
{
    private $mainService;

    public function __construct(User3EasyService $mainService)
    {
        $this->mainService = $mainService;
    }

    public function getUserAll()
    {
        try {
            // TODO: Get Data
            $user = $this->mainService->getUserAll();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data karyawan',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }

    public function getById($id)
    {
        try {
            // TODO: Get Data
            $user = $this->mainService->getById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data karyawan',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \App\Helpers\Log::exception($e);

            throw $e;
        }
    }
}
