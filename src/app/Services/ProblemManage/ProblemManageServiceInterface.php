<?php

namespace App\Services\ProblemManage;

interface ProblemManageServiceInterface
{
    public function getProblemManageAll(object $request);
    public function findById(int $id): object;
    public function detailProblem(int $id);
    public function exportProblemManage(object $request): object;
    public function createProblem(object $request);
    public function updateProblem(object $request, int $id);
    public function verifProblem(object $request, int $id);
}
