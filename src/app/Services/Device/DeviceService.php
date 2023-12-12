<?php

namespace App\Services\Device;

use App\Repositories\Device\DeviceRepository;
use App\Services\Device\DeviceServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DeviceService implements DeviceServiceInterface
{
    private $mainRepository;

    public function __construct(DeviceRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getDeviceAll(): object
    {
        $data = $this->mainRepository->getDeviceAll();
        return $data;
    }

    public function findById($id): object
    {
        $data = $this->mainRepository->findById($id);
        return $data;
    }

    public function exportDevice(): object
    {
        $data = $this->mainRepository->exportDevice();
        return $data;
    }

    public function createDevice(object $request): object
    {
        if (empty($request) || $request == null)
            throw new BadRequestException('Gagal create Device!');
        $data = $this->mainRepository->createDevice($request);
        return $data;
    }

    public function updateDevice(object $request, int $id): object
    {
        if (empty($request) || $request == null)
            throw new BadRequestException('Gagal update Device!');
        $data = $this->mainRepository->updateDevice($request, $id);
        return $data;
    }

    public function deleteDevice(int $id)
    {
        if(in_array($id, [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
            throw new BadRequestException("Data ini tidak boleh dihapus!");
        }
        $data = $this->mainRepository->deleteDevice($id);
        return $data;
    }
}
