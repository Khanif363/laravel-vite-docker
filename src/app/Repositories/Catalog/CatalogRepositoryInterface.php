<?php

namespace App\Repositories\Catalog;


interface CatalogRepositoryInterface
{
    public function getCatalogAll(object $request): object;
}
