<?php

namespace App\Repositories\Role;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface RoleRepositoryInterface
{
    public function createRole(object $request);
}
