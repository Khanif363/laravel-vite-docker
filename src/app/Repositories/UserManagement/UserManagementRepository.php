<?php

namespace App\Repositories\UserManagement;

use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserManagement\UserManagementRepositoryInterface;

class UserManagementRepository implements UserManagementRepositoryInterface
{
    use ImageTrait;

    private $model;


    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function rolePermission($id, $request)
    {
        $rolepermission = $this->model->with(
            [
                'role',
                'accessRights' => function ($query) use ($request) {
                    $query->when(isset($request['name']) ? $request['name'] : '', function ($query, $name) {
                        $query->where('access_name', 'LIKE', '%' . $name . '%');
                    });
                }
            ]
        )
            ->find($id);

        return $rolepermission;
    }

    public function getUserAll(): object
    {
        $user = $this->model->selectRaw('users.id, full_name, email, nik, departments.name as department_name, username, IF(is_enable = 1, "Active", "NonActive") as status')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->get();
        // dd($user);
        return $user;
    }

    public function getUserActive(): object
    {
        $user = $this->model
            ->where('is_enable', 1)
            ->get();
        // dd($user);
        return $user;
    }

    public function getEngineer() {
        $user = $this->model
            ->where('role_id', 4)
            ->where('is_enable', 1)
            ->get();
        // dd($user);
        return $user;
    }

    public function getEngineerManager()
    {
        return $this->model->select('id', 'full_name')
            ->whereIn('role_id', [3, 4])
            ->where('is_enable', 1)
            ->get();
    }

    public function getManager()
    {
        return $this->model->select('id', 'full_name')
            ->where('role_id', 3)
            ->where('is_enable', 1)
            ->get();
    }

    public function getGM()
    {
        return $this->model->select('id', 'full_name')
            ->where('role_id', 2)
            ->where('is_enable', 1)
            ->get();
    }


    public function createUser(object $request)
    {
        $dataUser = $request->except('permissions');
        $dataUser['password'] = Hash::make($dataUser['password']);
        //        $dataUser['image'] = $this->verifyAndUpload($request, $fieldname = 'user_image', $directory = 'user_image');

        $user = $this->model->create($dataUser);

        $dataPermission = $request->input('permissions', []);

        $user->accessRights()->attach($dataPermission);

        return $user;
    }

    public function updateUser(object $request, int $id): object
    {
        $user = $this->model->find($id);

        $dataUser = $request->except('permissions');
        //        $dataUser['image'] = $this->verifyAndUpload($request, $fieldname = 'user_image', $directory = 'user_image');

        if (!empty($dataUser['password'])) {
            $dataUser['password'] = Hash::make($dataUser['password']);
        } else {
            unset($dataUser['password']);
        }

        $user->update($dataUser);

        $dataPermission = $request->input('permissions', []);

        $user->accessRights()->sync($dataPermission);

        return $user;
    }

    public function getByRoleDepartment($request)
    {
        $user = $this->model
            ->where($request)
            ->get();
        return $user;
    }

    public function getOneUser(object $request)
    {
        return $this->model->where((array)$request)->first();
    }

    public function updateUserRAW(object $request, int $id)
    {
        $user = $this->model->findOrFail($id);

        return $user->update((array)$request);
    }

    public function updatePassword($request, $id)
    {
        $password = $this->model->find($id)->update([
            'password'  =>  Hash::make($request->password_confirmation),
        ]);
        return $password;
    }

    public function getEngineerAll()
    {
        return $this->model->select('id', 'full_name')
            ->whereIn('role_id', [3, 4])
            ->get();
    }

    public function exportUserManage()
    {
        $department = $this->model->query()
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->selectRaw('full_name, email, departments.name, roles.role_name, username, nik, IF(is_enable = 1, "Iya", "Tidak") as enable_status, last_login_on');
        return $department;
    }
}
