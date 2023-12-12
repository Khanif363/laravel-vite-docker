<?php

namespace App\Repositories\Service;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface ServiceRepositoryInterface
{
    public function getServiceAll(): object;
    public function getById($id);
    public function exportService();
    public function createService(object $request): object;
    public function updateService(object $request, int $id): object;
    public function deleteService(int $id);
}
