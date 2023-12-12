<?php

namespace App\Services\Catalog;

interface CatalogServiceInterface
{
    public function getCatalogAll(object $request): object;
}
