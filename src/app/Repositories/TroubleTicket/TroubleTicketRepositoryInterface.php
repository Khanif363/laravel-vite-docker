<?php

namespace App\Repositories\TroubleTicket;

interface TroubleTicketRepositoryInterface
{
    public function model();
    public function detailTicket(int $id): object;
    public function timeProgress(int $id);
    public function findById(int $id): object;
    public function findByNomor(string $nomor);
    public function findByIdDetail(int $id): object;
    public function exportTicket(object $request, $department): object;
    public function getTicketAll(object $request, $department);
    public function createTicket(object $request, string $nomor_ticket): array;
    public function updateProgress($request, $id): object;
    public static function lastDepartment($id);
    public function getAlert($request, $department);
    public function getTotalTicket($department);
    public function getMTTR($department);
    public function getMTTA();
    public function getTopTicket();
    public function getOpenMore3Days();
    public function getRate();
}
