<?php

namespace App\Services\Department;

use App\Repositories\Department\DepartmentRepository;
use App\Services\Department\DepartmentServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepartmentService implements DepartmentServiceInterface
{
    private $mainRepository;
    public function __construct(DepartmentRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getDepartmentAll(): object
    {
        $data = $this->mainRepository->getDepartmentAll();
        return $data;
    }

    public function getDepartmentActive(): object
    {
        $data = $this->mainRepository->getDepartmentActive();
        return $data;
    }

    public function getById(int $id)
    {
        $data = $this->mainRepository->getById($id);
        return $data;
    }

    public function exportDepartment()
    {
        $data = $this->mainRepository->exportDepartment();
        return $data;
    }

    public function createDepartment(object $request): object
    {
        $data = $this->mainRepository->createDepartment($request);
        return $data;
    }

    public function updateDepartment(object $request, int $id): object
    {
        $data = $this->mainRepository->updateDepartment($request, $id);
        return $data;
    }

    public function deleteDepartment(int $id)
    {
        if (in_array($id, [1, 2, 3, 4])) {
            throw new BadRequestException("Data ini tidak boleh dihapus!");
        }
        $data = $this->mainRepository->deleteDepartment($id);
        return $data;
    }

    public function getDepartmentByTipe($type)
    {
        switch ($type) {
            case 'core':
                return $this->getDepartmentCore();
            case 'semicore':
                return $this->getDepartmentSemiCore();

            default:
                throw new BadRequestException("Tipe tidak ditemukan!");
        }
    }

    public function getDepartmentCore()
    {
        return $this->mainRepository->getDepartmentCore();
    }

    public function getDepartmentSemiCore()
    {
        return $this->mainRepository->getDepartmentSemiCore();
    }
}
