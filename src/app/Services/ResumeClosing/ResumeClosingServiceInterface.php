<?php

namespace App\Services\ResumeClosing;

interface ResumeClosingServiceInterface
{
    public function getResumeClosingAll(): object;
    public function getById(int $id): object;
    public function exportResume(): object;
    public function createResumeClosing(object $request): object;
    public function updateResumeClosing(object $request, int $id): object;
    public function deleteResumeClosing(int $id);
}
