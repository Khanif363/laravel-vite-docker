<?php

namespace App\Repositories\Device;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface DeviceRepositoryInterface
{
    public function getDeviceAll(): object;
    public function findById($id): object;
    public function exportDevice(): object;
    public function createDevice(object $request): object;
    public function updateDevice(object $request, int $id): object;
    public function deleteDevice(int $id);
}
