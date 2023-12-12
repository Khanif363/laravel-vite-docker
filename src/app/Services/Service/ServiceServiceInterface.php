<?php

namespace App\Services\Service;

interface ServiceServiceInterface
{
    public function getServiceAll(): object;
    public function getById($id);
    public function exportService();
    public function createService(object $request): object;
    public function updateService(object $request, int $id): object;
    public function deleteService(int $id);
}
