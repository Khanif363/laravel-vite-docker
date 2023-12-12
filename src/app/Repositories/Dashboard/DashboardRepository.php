<?php

namespace App\Repositories\Dashboard;

use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;
use App\Repositories\Dashboard\DashboardRepositoryInterface;

class DashboardRepository extends BaseRepository implements DashboardRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        //return;
    }
}
