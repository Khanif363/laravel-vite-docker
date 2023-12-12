<?php

namespace App\Repositories\Location;

interface LocationRepositoryInterface
{
    public function getLocationAll(): object;
    public function getById(int $id);
    public function exportLocation();
    public function createLocation($request);
    public function updateLocation($request, int $id);
    public function deleteLocation(int $id);
}
