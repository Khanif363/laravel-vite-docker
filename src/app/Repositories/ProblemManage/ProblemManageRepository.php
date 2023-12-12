<?php

namespace App\Repositories\ProblemManage;

use App\Traits\ImageTrait;
use App\Models\ProblemManage;
use NamTran\LaravelMakeRepositoryService\Repository\BaseRepository;
use App\Repositories\ProblemManage\ProblemManageRepositoryInterface;

class ProblemManageRepository implements ProblemManageRepositoryInterface
{
    use ImageTrait;
    protected $model;

    public function __construct(ProblemManage $model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function getProblemManageAll(object $request, $department)
    {
        $problem = $this->model->query()->selectRaw('problem_manages.id, problem_manages.nomor_problem as nomor_problem, tt.nomor_ticket as nomor_ticket, tt.subject, tt.department_id, content, pmp_result.information as result, is_verified, pmp.progress_type as last_progress')
            ->leftJoin('trouble_tickets as tt', 'problem_manages.nomor_ticket', 'tt.nomor_ticket')
            ->leftJoin('problem_manage_progress as pmp', function ($join) {
                $join->on('problem_manages.id', '=', 'pmp.problem_manage_id')
                    ->orderBy('pmp.id', 'desc')->limit(1);
            })
            ->leftJoin('problem_manage_progress as pmp_result', function ($join) {
                $join->on('problem_manages.id', '=', 'pmp_result.problem_manage_id')
                    ->where('pmp_result.progress_type', 'Result')
                    ->orderBy('pmp.id', 'desc')->limit(1);
            })

            ->when($department != null, function ($query) use ($department) {
                $query->whereHas('troubleTicket.ttpDispatch.departmentDispatch', function ($query) use ($department) {
                    $query->whereIn('departments.id', $department)
                        ->where('trouble_ticket_progress.update_type', 'Dispatch')
                        ->orderBy('trouble_ticket_progress.id', 'desc')
                        ->limit(1);
                })
                    ->orWhereHas('troubleTicket.department', function ($query) use ($department) {
                        $query->whereIn('departments.id', $department);
                    });
            })

            ->groupBy('problem_manages.id')
            ->orderBy('problem_manages.id', 'desc')
            ->when($request->filled('status'), function ($problem) use ($request) {
                $problem->where('problem_manages.is_verified', $request->status);
            });

        if (!empty($request->nomor_problem))
            $problem->where('problem_manages.nomor_problem', $request->nomor_problem);
        if (!empty($request->nomor_ticket))
            $problem->where('tt.nomor_ticket', $request->nomor_ticket);
        if (!empty($request->subject))
            $problem->where('tt.subject', $request->subject);
        if (!empty($request->content))
            $problem->where('content', $request->content);
        if (!empty($request->result))
            $problem->where('pmp_result.information', $request->result);
        if (!empty($request->last_update))
            $problem->where('pmp.progress_type', $request->last_update);
        return $problem;
    }

    public function exportProblemManage(object $request): object
    {
        $problem = $this->model->query()->selectRaw('problem_manages.nomor_problem as nomor_problem, tt.nomor_ticket as nomor_ticket, tt.subject, content, pmp_result.information as result, CASE WHEN is_verified = 1 THEN "Approved" WHEN is_verified = 0 THEN "Await Approval" WHEN is_verified = 2 THEN "DisApproved" END as is_verified, pmp.progress_type as last_progress')
            ->leftJoin('trouble_tickets as tt', 'problem_manages.nomor_ticket', 'tt.nomor_ticket')
            ->leftJoin('problem_manage_progress as pmp', function ($join) {
                $join->on('problem_manages.id', '=', 'pmp.problem_manage_id')
                    ->orderBy('pmp.id', 'desc')->limit(1);
            })
            ->leftJoin('problem_manage_progress as pmp_result', function ($join) {
                $join->on('problem_manages.id', '=', 'pmp_result.problem_manage_id')
                    ->where('pmp_result.progress_type', 'Result')
                    ->orderBy('pmp.id', 'desc')->limit(1);
            })
            ->groupBy('problem_manages.id')
            ->orderBy('problem_manages.id', 'desc')
            ->when($request->filled('status'), function ($problem) use ($request) {
                $problem->where('problem_manages.is_verified', $request->status);
            });;

        if (!empty($request->nomor_problem))
            $problem->where('problem_manages.nomor_problem', $request->nomor_problem);
        if (!empty($request->nomor_ticket))
            $problem->where('tt.nomor_ticket', $request->nomor_ticket);
        if (!empty($request->subject))
            $problem->where('tt.subject', $request->subject);
        if (!empty($request->content))
            $problem->where('content', $request->content);
        if (!empty($request->result))
            $problem->where('pmp_result.information', $request->result);
        if (!empty($request->last_update))
            $problem->where('pmp.progress_type', $request->last_update);
        return $problem;
    }

    public function findById(int $id): object
    {
        $problem = $this->model->with('troubleTicket')->find($id);

        return $problem;
    }

    public function detailProblem(int $id)
    {
        $problem = $this->model->with([
            'troubleTicket' => function ($query) {
                $query->with([
                    'ttpDispatch' => function ($query) {
                        $query
                            ->with(['departmentDispatch' => function ($query) {
                                $query->select('departments.id', 'departments.name');
                            }])
                            ->where('update_type', 'Dispatch')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    },
                    'department' => function ($query) {
                        $query->select('*');
                    },
                    'service'   => function ($query) {
                        $query->select('*');
                    },
                    'ticketInfo'    => function ($query) {
                        $query->select('*');
                    }
                ]);
            },
            'lastProgress',
            'progressResult' => function ($query) {
                $query->where('progress_type', 'Result')
                    ->orderBy('id', 'desc')
                    ->limit(1);
            },
            'creator'   => function ($query) {
                $query->select('*');
            }
        ])->find($id);

        return $problem;
    }

    public function createProblem(object $request, string $nomor_problem, $verif): object
    {
        $problem = $this->model->create([
            'creator_id'                    => $request->creator_id,
            'nomor_ticket'                  => $request->nomor_ticket,
            'nomor_problem'                 => $nomor_problem,
            'problem_type'                  => $request->problem_type,
            'content'                       => $request->content,
            // 'is_verified'                   => in_array(auth()->user()->role_id, [2, 3]) ? 1 : 0
        ]+$verif);

        if ($request->hasFile('image_proof')) :
            $request['problem_manage_id_attachment'] = $problem->id;
            $request['inputer_id_attachment'] = $request->creator_id;
            $this->verifyAndUpload($request, 'image_proof', 'image_proof');
        endif;

        return $problem;
    }

    public function updateProblem(object $request, int $id): object
    {
        $problem = $this->model->find($id);
        $problem_progress = $problem->problem_manage_progress()->create([
            'progress_type'                     => $request->progress_type,
            'inputer_id'                       => $request->inputer_id,
            'information'                       => $request->information
        ]);

        $problem->update([
            'last_progress_id' => $problem_progress->id
        ]);

        if ($request->hasFile('image_proof')) :
            $request['problem_manage_progress_id_attachment'] = $problem_progress->id;
            $request['inputer_id_attachment'] = $request->inputer_id;
            $this->verifyAndUpload($request, 'image_proof', 'image_proof');
        endif;

        return $problem;
    }

    public function lastData()
    {
        $latest = $this->model->latest()->first();
        return $latest;
    }

    public function verifProblem($request, $id)
    {
        $problem = $this->model->find($id);

        $problem->update([
            'is_verified'      => $request->status
        ]);

        return $problem;
    }
}
