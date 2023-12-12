<?php

namespace App\Services\User3Easy;

interface User3EasyServiceInterface
{
    public function getUserAll(): object;
    public function getById($id): object;
}
