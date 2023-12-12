<?php

namespace App\Services\Catalog;

use App\Repositories\Catalog\CatalogRepository;
use App\Services\Catalog\CatalogServiceInterface;

class CatalogService implements CatalogServiceInterface
{
    private $mainRepository;

    public function __construct(CatalogRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getCatalogAll($request): object
    {
        $data = $this->mainRepository->getCatalogAll($request);
        return $data;
    }
}
