<?php

namespace App\Services\ResumeClosing;

use Exception;
use App\Repositories\ResumeClosing\ResumeClosingRepository;
use App\Services\ResumeClosing\ResumeClosingServiceInterface;

class ResumeClosingService implements ResumeClosingServiceInterface
{
    private $mainRepository;
    public function __construct(ResumeClosingRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getResumeClosingAll(): object
    {
        $data = $this->mainRepository->getResumeClosingAll();
        return $data;
    }

    public function getById(int $id): object
    {
        $data = $this->mainRepository->getById($id);
        return $data;
    }

    public function exportResume(): object
    {
        $data = $this->mainRepository->exportResume();
        return $data;
    }

    public function createResumeClosing(object $request): object
    {
        if (empty($request) || $request == null)
            throw new Exception('Gagal create Resume');
        $data = $this->mainRepository->createResumeClosing($request);
        return $data;
    }

    public function updateResumeClosing(object $request, int $id): object
    {
        if (empty($request) || $request == null)
            throw new Exception('Gagal update Resume');
        $data = $this->mainRepository->updateResumeClosing($request, $id);
        return $data;
    }

    public function deleteResumeClosing(int $id)
    {
        $data = $this->mainRepository->deleteResumeClosing($id);
        return $data;
    }
}
