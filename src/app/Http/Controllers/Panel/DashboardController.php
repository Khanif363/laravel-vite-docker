<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Department\DepartmentService;
use App\Services\UserManagement\UserManagementService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DashboardController extends Controller
{
    private $logActivity;
    private $userService;
    private $data_view;

    public function __construct(LogActivity $logActivity, UserManagementService $userService, DepartmentService $departmentService)
    {
        $this->logActivity = $logActivity;
        $this->userService = $userService;
        $this->data_view = [
            'departments'                   => $departmentService->getDepartmentActive(),
            'departments_core'              => $departmentService->getDepartmentCore(),
        ];
    }
    public function index()
    {
        $menu = ['utama' => 'Dashboard'];
        $descrip = ['description' => 'View Dashboard'];
        $data_view = $this->data_view;

        $rolepermission = $this->userService->rolePermission();
        if (!in_array('View Dashboard', (array)$rolepermission->accessRights->pluck('access_name')->toArray()) && (($rolepermission->role->role_name ?? null) != 'Admin')) :
            throw new BadRequestException("Anda tidak memiliki akses untuk melihat Dashboard!");
        endif;

        try {
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view('pages.dashboard.index', compact('menu', 'data_view'));
        } catch (BadRequestException $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('error', $ex->getMessage());
        } catch (\Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message' => $ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }
}
