<?php

namespace App\Repositories\LogActivity;

use App\Models\LogActivity;
use App\Repositories\LogActivity\LogActivityRepositoryInterface;

class LogActivityRepository implements LogActivityRepositoryInterface
{
    private $model;

    public function __construct(LogActivity $model)
    {
        $this->model = $model;
    }

    public function createLog(array $log)
    {
        return $this->model->create($log);
    }
}
