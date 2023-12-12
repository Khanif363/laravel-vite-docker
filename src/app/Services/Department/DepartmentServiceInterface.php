<?php

namespace App\Services\Department;

interface DepartmentServiceInterface
{
    public function getById(int $id);
    public function getDepartmentAll(): object;
    public function exportDepartment();
    public function createDepartment(object $request);
    public function updateDepartment(object $request, int $id): object;
    public function deleteDepartment(int $id);
}
