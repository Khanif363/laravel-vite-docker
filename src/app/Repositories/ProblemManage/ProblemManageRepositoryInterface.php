<?php

namespace App\Repositories\ProblemManage;

use NamTran\LaravelMakeRepositoryService\Repository\RepositoryContract;

interface ProblemManageRepositoryInterface
{
    public function getProblemManageAll(object $request, $department);
    public function findById(int $id): object;
    public function detailProblem(int $id);
    public function exportProblemManage(object $request): object;
    // public function createProblem(object $request, string $nomor_problem): object;
    public function updateProblem(object $request, int $id): object;
    public function lastData();
    public function verifProblem(object $request, int $id);
}
