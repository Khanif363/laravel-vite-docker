<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Log;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\UserManagement\UserManagementService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $mainService;
    private $_route = 'auth.';
    private $_view = 'pages.auth.';
    private $logActivity;

    public function __construct(UserManagementService $mainService, LogActivity $logActivity)
    {
        $this->mainService = $mainService;
        $this->logActivity = $logActivity;
    }

    /**
     * Show login page.
     *
     * @return Factory|View|Application
     */
    public function login()
    {

        $data = [
            'route' => $this->_route . 'login',
            'title' => 'Login'
        ];

        return view($this->_view . __FUNCTION__, $data);
    }

    /**
     * Authenticate user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function loginProcess(LoginRequest $request): JsonResponse
    {
        $descrip = ['description'=> 'Login'];
        try {
            $this->mainService->login($request);

            $user = $this->mainService->rolePermission();
            $access_user = $user->accessRights->pluck('access_name')->toArray() ?? [];
            $role_user = $user->role->role_name ?? null;
            $route_list = [
                '/home' => 'View Dashboard',
                'tickets.index' => 'View List Ticket',
                'problem-managements.index' => 'View List Root Cause Analysis',
                'change-managements.index' => 'View List Changes',
                'catalogs.index' => 'View List Katalog',
                'departments.index' => 'View List Department',
                'services.index' => 'View List Service',
                'resumes.index' => 'View List Resume',
                'devices.index' => 'View List Device',
                'users.index' => 'View List User Management',
            ];
            $compare_access = array_intersect($route_list, ($access_user ?? []));
            $route = array_keys($compare_access)[0] ?? null;

            if (empty($route) && $role_user != 'Admin') {
                throw new BadRequestException('Anda tidak memiliki akses untuk apapun, Hubungi Service Desk!');
            }

            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            return response()->json([
                'success' => true,
                'message' => 'Login Success',
                'data' => [
                    // 'route' => route(RouteServiceProvider::HOME)
                    'route' => route($route ?? RouteServiceProvider::HOME)
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            throw $e;
        }
    }

    /**
     * Logout user.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function logout(Request $request)
    {
        $descrip = ['description'=> 'Logout'];
        try {
            $this->logActivity->createLog(['status' => 'success'] + $descrip);
            session()->flush();
            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Logout Success',
                'data' => [
                    'route' => route('auth.login.show')
                ]
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);
            $this->logActivity->createLog(['status' => 'error', 'error_message'=>$e] + $descrip);
            throw $e;
        }
    }

    public function refreshSession(): int
    {
        $limitSession = floor(strtotime(date('Y-m-d H:i:s') . '+ 2 hours') / 10);

        session()->put([
            'last_check' => session()->get('last_check'),
            'limitSession' => $limitSession,
        ]);

        return print($limitSession);
    }
}
