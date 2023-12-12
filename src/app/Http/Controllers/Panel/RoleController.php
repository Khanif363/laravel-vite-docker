<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    private $mainService;

    public function __construct(RoleService $mainService)
    {
        $this->mainService = $mainService;
    }

    public function createRole(Request $request)
    {
        try {
            $role = $this->mainService->createRole($request);
            return $role;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

    public function updateRole(Request $request, $id)
    {
        try {
            $data = $this->mainService->updateRole($request, $id);
            return $data;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }
}
