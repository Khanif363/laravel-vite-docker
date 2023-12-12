<?php

namespace App\Services\Service;

use Exception;
use App\Repositories\Service\ServiceRepository;
use App\Services\Service\ServiceServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ServiceService implements ServiceServiceInterface
{
    private $mainRepository;

    public function __construct(ServiceRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getServiceAll(): object
    {
        $data = $this->mainRepository->getServiceAll();
        return $data;
    }

    public function getById($id)
    {
        $data = $this->mainRepository->getById($id);
        return $data;
    }

    public function exportService()
    {
        $data = $this->mainRepository->exportService();
        return $data;
    }

    public function createService(object $request): object
    {
        if (empty($request) || $request == null)
            throw new Exception('Gagal create Service');
        $data = $this->mainRepository->createService($request);
        return $data;
    }

    public function updateService(object $request, int $id): object
    {
        if (empty($request) || $request == null)
            throw new Exception('Gagal update Service');
        $data = $this->mainRepository->updateService($request, $id);
        return $data;
    }

    public function deleteService(int $id)
    {
        if(in_array($id, [1, 2, 3, 4, 5]))
        throw new BadRequestException('Data ini tidak boleh dihapus!');

        $data = $this->mainRepository->deleteService($id);
        return $data;
    }
}
