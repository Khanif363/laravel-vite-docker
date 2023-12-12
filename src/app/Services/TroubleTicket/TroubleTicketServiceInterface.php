<?php

namespace App\Services\TroubleTicket;

interface TroubleTicketServiceInterface
{
    public function getTicketAll(object $request): object;
    public function findById(int $id);
    public function findByNomor(string $nomor);
    public function detailTicket(int $id): object;
    public function exportTicket(object $request): object;
    public function createTicket(object $request);
    public function updateProgress(object $request, int $id);
    public function getAlert($request);
    public function sendEmail($request, $id);
    public function getTotalTicket($department);
    public function getMTTR($department);
    public function getMTTA();
    public function getTopTicket();
    public function getOpenMore3Days();
    public function getRate();
}
