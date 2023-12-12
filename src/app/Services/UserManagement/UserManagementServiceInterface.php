<?php

namespace App\Services\UserManagement;

interface UserManagementServiceInterface
{
    public function getUserAll(): object;
    public function createUser(object $request);
    public function updateUser(object $request, int $id): object;
}
