<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserManagement\UserManagementService;

class Permission
{

    protected $userService;

    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $permission_query = $this->userService->rolePermission();

        $permission = [
            'role' => $permission_query->role->role_name ?? null,
            'access_right'  => $permission_query->accessRights->pluck('access_name') ?? []
        ];


        $access_with_department = array_map(function ($key, $value) {
            $perm = [
                'name' => $value['access_name'] ?? 0,
                'department' => $value['department_id'] ?? 0,
            ];

            return $perm;
        }, array_keys($permission_query->accessRights->toArray()), $permission_query->accessRights->toArray());

        // $access_with_department = array_combine(
        //     array_map(function ($key) {
        //         return 'access-' . $key;
        //     }, array_keys($access_with_department)),
        //     $access_with_department
        // );


        $access_info = [];
        foreach ($access_with_department as $access) {
            $access_name = $access['name'];
            $department_id = $access['department'];
            if (!isset($access_info[$access_name])) {
                $access_info[$access_name] = [];
            }
            $access_info[$access_name][$department_id] = true;
        }

        // dd($access_info);

        $search_str = ['View ', 'Root Cause Analysis', 'User Management'];
        $replace_str = ['', 'RCA', 'User'];
        $submenu_middleware = json_decode(str_replace($search_str, $replace_str, ($permission['access_right'] ?? null)), true);

        view()->share([
            'permission'    => $permission,
            'submenu_middleware'    => $submenu_middleware,
            'access_with_department' => $access_info
        ]);

        // dd($permission);

        return $next($request);
    }
}
