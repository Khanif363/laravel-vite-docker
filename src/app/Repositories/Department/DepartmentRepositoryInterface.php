<?php

namespace App\Repositories\Department;

interface DepartmentRepositoryInterface
{
    public function getById(int $id);
    public function getDepartmentAll(): object;
    public function exportDepartment();
    public function createDepartment(object $request): object;
    public function updateDepartment(object $request, int $id): object;
    public function deleteDepartment(int $id);
}
