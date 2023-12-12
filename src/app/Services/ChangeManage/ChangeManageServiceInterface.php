<?php

namespace App\Services\ChangeManage;

interface ChangeManageServiceInterface
{
    public function getCRAll($request): object;
    public function detailCR(int $id);
    public function verifCR(object $request, int $id);
    public function exportCR(object $request): object;
    public function exportPdfById(int $id): object;
    public function createChange($request);
    public function getDatacomLocation(): object;
    public function getUserCR(): object;
}
