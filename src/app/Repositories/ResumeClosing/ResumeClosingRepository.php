<?php

namespace App\Repositories\ResumeClosing;

use App\Models\Resume;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;
use App\Repositories\ResumeClosing\ResumeClosingRepositoryInterface;

class ResumeClosingRepository implements ResumeClosingRepositoryInterface
{
    protected $model;

    public function __construct(Resume $model)
    {
        $this->model = $model;
    }

    public function getResumeClosingAll(): object
    {
        $resume = $this->model->all();
        return $resume;
    }

    public function getById(int $id): object
    {
        $resume = $this->model->find($id);
        return $resume;
    }

    public function exportResume(): object
    {
        $resume = $this->model->query()->selectRaw('departments.name as department_name, resumes.name as resume_name, IF(resumes.status = 1, "Active", "NonActive") as status')
            ->join('departments', 'resumes.department_id', '=', 'departments.id');
        return $resume;
    }

    public function createResumeClosing(object $request): object
    {
        $resume = $this->model->create([
            'department_id'             => $request->department_id,
            'name'                      => $request->name,
            'status'                    => $request->status ?? 1,
        ]);

        return $resume;
    }

    public function updateResumeClosing(object $request, int $id): object
    {
        $resume = $this->model->find($id);
        $resume->update([
            'department_id'             => $request->department_id,
            'name'                      => $request->name,
            'status'                    => $request->status ?? 1,
        ]);

        return $resume;
    }

    public function deleteResumeClosing(int $id)
    {
        $resume = $this->model->find($id);
        $resume->delete();

        return $resume;
    }
}
