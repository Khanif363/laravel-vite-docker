<?php

namespace App\Repositories\User3Easy;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface User3EasyRepositoryInterface
{
    public function getUserAll(): object;
    public function getById($id): object;
}
