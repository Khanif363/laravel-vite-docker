<?php

namespace App\Repositories\Role;

use App\Models\Role;
use App\Repositories\Role\RoleRepositoryInterface;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function createRole(object $request)
    {
        $role = $this->model->create([
            'role_name'         => $request->role_name
        ]);

        return $role;
    }

    public function updateRole(object $request, int $id): object
    {
        $role = $this->model->find($id);

        $role->update([
            'role_name'         => $request->role_name,
            'is_active'         => $request->is_active
        ]);

        return $role;
    }
}
