<?php

namespace App\Repositories\Service;

use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function getServiceAll(): object
    {
        $service = $this->model->with('department')->get();
        return $service;
    }

    public function getById($id)
    {
        $service = $this->model->with('department')->find($id);
        return $service;
    }

    public function exportService()
    {
        $service = $this->model->query()->selectRaw('departments.name as department_name, services.name as service_name, IF(services.status = 1, "Iya", "Tidak") as status_service')
            ->join('departments', 'services.department_id', '=', 'departments.id');
        return $service;
    }

    public function createService(object $request): object
    {
        $service = $this->model->create([
            'department_id'         => $request->department_id,
            'name'                  => $request->name,
            'category'                  => $request->category,
            'status'                => $request->status ?? 1
        ]);

        return $service;
    }

    public function updateService(object $request, int $id): object
    {
        $service = $this->model->find($id);

        $service->update([
            'department_id'         => $request->department_id,
            'name'                  => $request->name,
            'category'              => $request->category,
            'status'                => $request->status
        ]);

        return $service;
    }

    public function deleteService(int $id)
    {
        $service = $this->model->find($id);
        $service->delete();

        return $service;
    }
}
