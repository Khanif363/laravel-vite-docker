<?php

namespace App\Services\Location;

interface LocationServiceInterface
{
    public function getLocationAll(): object;
    public function getById(int $id);
    public function exportLocation();
    public function createLocation($request);
    public function updateLocation($request, int $id);
    public function deleteLocation(int $id);
}
