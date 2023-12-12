<?php

namespace App\Services\Role;

interface RoleServiceInterface
{
    public function createRole(object $request);
    public function updateRole(object $request, int $id): object;
}
