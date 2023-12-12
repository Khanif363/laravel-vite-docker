<?php

namespace App\Repositories\Catalog;

use App\Models\Catalog;
use App\Repositories\Catalog\CatalogRepositoryInterface;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;

class CatalogRepository implements CatalogRepositoryInterface
{

    protected $model;

    public function __construct(Catalog $model)
    {
        $this->model = $model;
    }

    public function getCatalogAll($request): object
    {
        $catalogs = $this->model->search($request->title)->get();
        return $catalogs;
    }
}
