<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    private $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function getById(int $id)
    {
        $department = $this->model->find($id);
        return $department;
    }

    public function getDepartmentAll(): object
    {
        $department = $this->model->selectRaw('id, name, is_desk, IF(status = 1, "Active", "NonActive") as status, is_core')->get();
        return $department;
    }

    public function getDepartmentActive()
    {
        return $this->model->select('id', 'name')
            ->where('status', 1)
            ->get();
    }

    public function exportDepartment()
    {
        $department = $this->model->query()->selectRaw('name, IF(status = 1, "Active", "NonActive") as status, IF(is_desk = 1, "Iya", "Tidak") as is_desk, IF(is_core = 1, "Iya", "Tidak") as is_core');
        return $department;
    }

    public function createDepartment(object $request): object
    {
        $department = $this->model->create([
            'name'        => $request->name,
            'status'        => $request->status ?? 1,
            'is_desk'        => $request->is_desk ?? 0,
            'is_core'        => $request->is_core ?? 0,
        ]);

        return $department;
    }

    public function updateDepartment(object $request, int $id): object
    {
        $department = $this->model->find($id);

        $department->update([
            'name'        => $request->name,
            'status'        => $request->status,
            'is_desk'        => $request->is_desk,
            'is_core'        => $request->is_core,
        ]);

        return $department;
    }

    public function deleteDepartment(int $id)
    {
        $department = $this->model->find($id);
        $department->delete();
    }

    public function getDepartmentCore()
    {
        return $this->model->select('id', 'name')
            ->where('status', 1)
            ->where('is_core', 1)
            ->get();
    }

    public function getDepartmentSemiCore() {
        return $this->model->select('id', 'name')
            ->where('status', 1)
            ->where('is_core', 1)
            ->orWhere('is_desk', 1)
            ->get();
    }
}
