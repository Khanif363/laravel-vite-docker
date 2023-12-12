<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepository;
use App\Services\Role\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{
    private $mainRepository;

    public function __construct(RoleRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function createRole(object $request)
    {
        $data = $this->mainRepository->createRole($request);
        return $data;
    }

    public function updateRole(object $request, int $id): object
    {
        $data = $this->mainRepository->updateRole($request, $id);
        return $data;
    }
}
