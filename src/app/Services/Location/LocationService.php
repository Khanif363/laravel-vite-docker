<?php

namespace App\Services\Location;

use App\Repositories\Location\LocationRepository;
use App\Services\Location\LocationServiceInterface;

class LocationService implements LocationServiceInterface
{
    private $mainRepository;
    public function __construct(LocationRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getLocationAll(): object
    {
        $data = $this->mainRepository->getLocationAll();
        return $data;
    }

    public function getById(int $id)
    {
        $data = $this->mainRepository->getById($id);
        return $data;
    }

    public function exportLocation()
    {
        $data = $this->mainRepository->exportLocation();
        return $data;
    }

    public function createLocation($request)
    {
        $data = $this->mainRepository->createLocation($request);
        return $data;
    }

    public function updateLocation($request, int $id)
    {
        $data = $this->mainRepository->updateLocation($request, $id);
        return $data;
    }

    public function deleteLocation(int $id)
    {
        $data = $this->mainRepository->deleteLocation($id);
        return $data;
    }
}
