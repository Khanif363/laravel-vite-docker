<?php

namespace App\Repositories\UserManagement;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface UserManagementRepositoryInterface
{
    public function getUserAll(): object;
    public function createUser(object $request);
    public function updateUser(object $request, int $id): object;
}
