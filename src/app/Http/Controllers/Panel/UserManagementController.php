<?php

namespace App\Http\Controllers\Panel;

use Exception;
use App\Helpers\Log;
use App\Models\Role;
use App\Models\User;
use App\Helpers\SelectView;
use App\Models\AccessRight;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Exports\UserManageExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\UserManagement\UserManagementService;

class UserManagementController extends Controller
{
    private $mainService;
    private $logActivity;
    private $menu;
    private $route_prefix = 'users.';
    private $view_prefix = 'pages.user-management.';

    public function __construct(UserManagementService $mainService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
        $this->menu = [
            'utama'     => 'User Management',
            'opsi'      => [
                [
                    'nama' => 'List User',
                    'link' => '/user-managements'
                ],
                [
                    'nama' => 'Create User',
                    'link' => '/user-managements/create'
                ]
            ]
        ];
    }

    public function index()
    {
        $menu = $this->menu;
        $menu['sub'] = "List User";
        $descrip = ['description'=> 'View User Management'];

        try {
            $data = $this->mainService->getUserAll();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return view($this->view_prefix . 'index', compact('menu', 'data'));
        } catch (Exception $ex) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$ex] + $descrip);
            return back()->with('Tidak dapat ditampilkan');
        }
    }

    public function viewCreate()
    {
        $data = [
            'menu' => [
                'sub' => 'Create User'
            ]+$this->menu,
            'departments' => SelectView::departmentAll(),
            'roles' => Role::all(),
            'accessRights' => AccessRight::all()
        ];

        return view($this->view_prefix . 'create', $data);
    }

    public function viewUpdate(User $user)
    {
        $data = [
            'menu' => [
                'sub' => 'Edit User',
            ]+$this->menu,
            'user' => $user->with('accessRights')->findOrFail($user->id),
            'departments' => SelectView::departmentAll(),
            'roles' => Role::all(),
            'accessRights' => AccessRight::all()
        ];

        return view($this->view_prefix . 'edit', $data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function createUser(Request $request): JsonResponse
    {
        $descrip = ['description'=> 'Create User Management'];
        try {
            DB::beginTransaction();

            $this->mainService->createUser($request);

            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);

            return response()->json([
                'success' => true,
                'message' => 'Create Data Success',
                'data' => [
                    'route' => route($this->route_prefix.'index')
                ]
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $descrip = ['description'=> 'Update User Management'];
        try {
            DB::beginTransaction();

            $this->mainService->updateUser($request, $user->id);

            DB::commit();
            $this->logActivity->createLog(['status' => 'success'] + $descrip);

            return response()->json([
                'success' => true,
                'message' => 'Edit Data Success',
                'data' => [
                    'route' => route($this->route_prefix.'index')
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    public function updatePassword(Request $request)
{
        $descrip = ['description'=> 'Update Password'];
        try {
            $this->mainService->updatePassword($request);
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return response()->json([
                'success' => true,
                'message' => 'Ubah Password Success',
                'data' => [
                    'route' => route('auth.login.show')
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    public function export(Request $request)
    {
        return (new UserManageExport($this->mainService))->download("users" . ".$request->format");
    }
}
