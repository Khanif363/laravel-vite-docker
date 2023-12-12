<?php

namespace App\Repositories\Device;

use App\Models\Device;
use App\Repositories\Device\DeviceRepositoryInterface;

class DeviceRepository implements DeviceRepositoryInterface
{
    private $model;

    public function __construct(Device $model)
    {
        $this->model = $model;
    }

    public function getDeviceAll(): object
    {
        $device = $this->model->all();
        return $device;
    }

    public function findById($id): object
    {
        $device = $this->model->find($id);
        return $device;
    }

    public function exportDevice(): object
    {
        $device = $this->model->query()->selectRaw('name, brand, type, hostname, sn, IF(status = 1, "Iya", "Tidak") as status');
        return $device;
    }

    public function createDevice(object $request): object
    {
        $device = $this->model->create([
            'name'              => $request->name,
            'brand'             => $request->brand,
            'type'              => $request->type,
            'hostname'          => $request->hostname,
            'sn'                => $request->sn,
            'status'            => $request->status ?? 1,
        ]);
        return $device;
    }

    public function updateDevice(object $request, int $id): object
    {
        $device = $this->model->find($id);
        $device->update([
            'name'              => $request->name,
            'brand'             => $request->brand,
            'type'              => $request->type,
            'hostname'          => $request->hostname,
            'sn'                => $request->sn,
            'status'            => $request->status ?? 1,
        ]);

        return $device;
    }

    public function deleteDevice(int $id)
    {
        $device = $this->model->find($id);
        $device->delete();

        return $device;
    }
}
