<?php

namespace App\Repositories\Location;

use App\Models\Location;
use App\Repositories\Location\LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface
{
    private $model;

    public function __construct(Location $model)
    {
        $this->model = $model;
    }

    public function getLocationAll(): object
    {
        $location = $this->model->selectRaw('id, name')->get();
        return $location;
    }

    public function getById(int $id)
    {
        $locations = $this->model->find($id);
        return $locations;
    }

    public function exportLocation()
    {
        $location = $this->model->query()->select('name');
        return $location;
    }

    public function createLocation($request)
    {
        $location = $this->model->create($request);
        return $location;
    }


    public function updateLocation($request, int $id): object
    {
        $location = $this->model->find($id);
        $location->update($request);
        return $location;
    }

    public function deleteLocation(int $id)
    {
        $location = $this->model->find($id)->delete();
        return $location;
    }
}
