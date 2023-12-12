<?php

namespace App\Repositories\LogActivity;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface LogActivityRepositoryInterface
{
    public function createLog(array $log);
}
