<?php

namespace App\Services\User3Easy;

use App\Repositories\User3Easy\User3EasyRepository;
use App\Services\User3Easy\User3EasyServiceInterface;

class User3EasyService implements User3EasyServiceInterface
{
    protected $mainRepository;

    public function __construct(User3EasyRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getUserAll(): object
    {
        $data = $this->mainRepository->getUserAll();
        return $data;
    }

    public function getById($id): object
    {
        $data = $this->mainRepository->getById($id);
        return $data;
    }
}
