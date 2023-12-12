<?php

namespace App\Repositories\User3Easy;

use App\Models\User3Easy;
use App\Repositories\User3Easy\User3EasyRepositoryInterface;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;

class User3EasyRepository implements User3EasyRepositoryInterface
{
    private $model;

    public function __construct(User3Easy $model)
    {
        $this->model = $model;
    }

    public function getUserAll(): object
    {
        $user = $this->model->select('nama', 'id')->get();
        return $user;
    }

    public function getById($id): object
    {
        $user = $this->model->find($id);
        return $user;
    }
}
