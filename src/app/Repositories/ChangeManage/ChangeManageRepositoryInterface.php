<?php

namespace App\Repositories\ChangeManage;

interface ChangeManageRepositoryInterface
{
    public function getCRAll(object $request): object;
    public function detailCR(int $id);
    public function verifCR(object $request, int $id);
    public function exportCR(object $request): object;
    public function createChange($data_request, $request): array;
    public function getDatacomLocation(): object;
    public function getUserCR(): object;
}
