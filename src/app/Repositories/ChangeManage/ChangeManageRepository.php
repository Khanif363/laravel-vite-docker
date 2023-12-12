<?php

namespace App\Repositories\ChangeManage;

use DateTime;
use Exception;

use Carbon\Carbon;
use App\Traits\Action;
use App\Models\Comment;
use App\Traits\ImageTrait;
use App\Models\ChangeManage;
use App\Models\ChangeManageProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use App\Repositories\ChangeManage\ChangeManageRepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ChangeManageRepository implements ChangeManageRepositoryInterface
{
    use ImageTrait;
    use Action;
    protected $model;
    protected $comment;
    protected $changeManageProgress;

    public function __construct(ChangeManage $model, Comment $comment, ChangeManageProgress $changeManageProgress)
    {
        $this->model = $model;
        $this->comment = $comment;
        $this->changeManageProgress = $changeManageProgress;
    }

    public function model()
    {
        return $this->model;
    }

    public function getCRAll($request): object
    {

        $changes = $this->model->query()
            ->where('change_manages.is_deleted', 0)
            ->selectRaw('change_manages.id, change_manages.approval_level1_id, change_manages.approval_level2_id,  change_manages.nomor_changes, change_manages.title, change_manages.status, change_manages.type, change_manages.reference, change_manages.pic_telkomsat, change_manages.pic_nontelkomsat, change_manages.agenda, change_manages.datetime_action, change_manages.content, change_manages.created_date, change_manages.last_updated_date, change_manages.closed_date, change_manages.is_draft, cmp.progress_type as last_progress, app1.status_approval as status_approval1, app2.status_approval as status_approval2')
            ->leftJoin('change_manage_progress as cmp', 'change_manages.last_progress_id', '=', 'cmp.id')
            ->leftJoin('change_manage_progress as cmp_approve_level1', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level1.change_manage_id')
                    ->where('cmp_approve_level1.progress_type', '=', 'Approval By Manager')
                    ->whereRaw('cmp_approve_level1.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By Manager"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })
            ->leftJoin('change_manage_progress as cmp_approve_level2', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level2.change_manage_id')
                    ->where('cmp_approve_level2.progress_type', '=', 'Approval By GM')
                    ->whereRaw('cmp_approve_level2.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By GM"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })

            ->leftJoin('approvals as app1', function (JoinClause $join) {
                $join->on('cmp_approve_level1.id', '=', 'app1.approvalable_id')
                    ->where('app1.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->leftJoin('approvals as app2', function (JoinClause $join) {
                $join->on('cmp_approve_level2.id', '=', 'app2.approvalable_id')
                    ->where('app2.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->groupBy('change_manages.id')
            ->when($request->filled('request.nomor_changes'), function ($query) use ($request) {
                $query->where('nomor_changes', '=', $request->input('request.nomor_changes'));
            })
            ->when($request->filled('request.title'), function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->input('request.title') . '%');
            })
            ->when($request->filled('request.type'), function ($query) use ($request) {
                $query->where('type', 'LIKE', '%' . json_encode($request->input('request.type')) . '%');
            })
            ->when($request->filled('request.priority'), function ($query) use ($request) {
                $query->where('priority', '=', $request->input('request.priority'));
            })
            ->when($request->filled('request.datetime_action'), function ($query) use ($request) {
                $query->whereDate('datetime_action', '=', Carbon::parse($request->input('request.datetime_action'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.pic_telkomsat'), function ($query) use ($request) {
                $query->where('pic_telkomsat', 'LIKE', '%' . $request->input('request.pic_telkomsat') . '%');
            })
            ->when($request->filled('request.pic_nontelkomsat'), function ($query) use ($request) {
                $query->where('pic_nontelkomsat', 'LIKE', '%' . $request->input('request.pic_nontelkomsat') . '%');
            })
            ->when($request->filled('request.agenda'), function ($query) use ($request) {
                $query->where('agenda', 'LIKE', '%' . $request->input('request.agenda') . '%');
            })
            ->when($request->filled('request.approval_level1_id'), function ($query) use ($request) {
                $query->where('approval_level1_id', '=', $request->input('request.approval_level1_id'));
            })
            ->when($request->filled('request.approval_level2_id'), function ($query) use ($request) {
                $query->where('approval_level2_id', '=', $request->input('request.approval_level2_id'));
            })
            ->when($request->filled('request.status_approval1'), function ($query) use ($request) {
                $query->where('status_approval1', '=', $request->input('request.status_approval1'));
            })
            ->when($request->filled('request.status_approval2'), function ($query) use ($request) {
                $query->where('status_approval2', '=', $request->input('request.status_approval2'));
            })
            ->when($request->filled('request.last_updated_date'), function ($query) use ($request) {
                $query->whereDate('last_updated_date', '=', Carbon::parse($request->input('request.last_updated_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.closed_date'), function ($query) use ($request) {
                $query->whereDate('closed_date', '=', Carbon::parse($request->input('request.closed_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.location_id'), function ($query) use ($request) {
                $query->where('location_id', '=', $request->input('request.location_id'));
            })
            ->when($request->filled('request.creator_id'), function ($query) use ($request) {
                $query->where('creator_id', '=', $request->input('request.creator_id'));
            })
            ->when($request->filled('request.created_date'), function ($query) use ($request) {
                $query->whereDate('created_date', '=', Carbon::parse($request->input('request.created_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.status'), function ($query) use ($request) {
                $request_status = $request->input('request.status');
                if ($request_status == 'draft')
                    $query->where('change_manages.is_draft', 1);
                if ($request_status == 'completed')
                    $query->where('change_manages.status', 'Closed');
                if ($request_status == 'verification')
                    $query
                        ->where('is_draft', 0)
                        ->where(function ($query) {
                            $query->where('app1.status_approval', 2)
                                ->where('change_manages.is_deleted', 0)
                                ->orWhereNull('app1.status_approval');
                        })
                        ->orWhere(function ($query) {
                            $query->where('app2.status_approval', 2)
                                ->where('change_manages.is_deleted', 0)
                                ->whereIn('cmp.progress_type', ['Edit', 'Approval By GM']);
                        });
                if ($request_status == 'approval')
                    $query
                        ->where(function ($query) {
                            $query->where('app1.status_approval', 1)
                                ->where('change_manages.is_deleted', 0)
                                ->where(function ($query) {
                                    $query->whereNull('app2.status_approval')
                                        ->orWhere(function ($query) {
                                            $query->where('app2.status_approval', 2)
                                                ->where('change_manages.is_deleted', 0)
                                                ->where('cmp.progress_type', 'Approval By Manager');
                                        });
                                });
                        })->where('change_manages.status', 'Open');
            });

        return $changes;
    }
    public function getCRAllOnly(): object
    {

        $changes = $this->model->query()->selectRaw('change_manages.*, cmp.progress_type as last_progress, app1.status_approval as status_approval1, app2.status_approval as status_approval2')
            ->where('is_deleted', 0)
            ->leftJoin('change_manage_progress as cmp', 'change_manages.last_progress_id', '=', 'cmp.id')
            ->leftJoin('change_manage_progress as cmp_approve_level1', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level1.change_manage_id')
                    ->where('cmp_approve_level1.progress_type', '=', 'Approval By Manager')
                    ->whereRaw('cmp_approve_level1.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By Manager"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })
            ->leftJoin('change_manage_progress as cmp_approve_level2', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level2.change_manage_id')
                    ->where('cmp_approve_level2.progress_type', '=', 'Approval By GM')
                    ->whereRaw('cmp_approve_level2.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By GM"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })

            ->leftJoin('approvals as app1', function (JoinClause $join) {
                $join->on('cmp_approve_level1.id', '=', 'app1.approvalable_id')
                    ->where('app1.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->leftJoin('approvals as app2', function (JoinClause $join) {
                $join->on('cmp_approve_level2.id', '=', 'app2.approvalable_id')
                    ->where('app2.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->groupBy('change_manages.id');

        return $changes->get();
    }

    public function detailCR(int $id)
    {
        $changes = $this->model
            ->leftJoin('users as user_app1', function ($join) {
                $join->on('change_manages.approval_level1_id', '=', 'user_app1.id')
                    ->leftJoin('departments as department_manager', function ($join) {
                        $join->on('user_app1.department_id', '=', 'department_manager.id');
                    });
            })
            ->leftJoin('users as user_app2', function ($join) {
                $join->on('change_manages.approval_level2_id', '=', 'user_app2.id');
            })
            ->leftJoin('users as crt', function ($join) {
                $join->on('change_manages.creator_id', '=', 'crt.id');
            })
            ->leftJoin('locations as lc', function ($join) {
                $join->on('change_manages.location_id', '=', 'lc.id');
            })
            ->leftJoin('users as en', function ($join) {
                $join->on('change_manages.engineer_id', '=', 'en.id');
            })


            ->leftJoin('change_manage_progress as cmp_approve_level1', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level1.change_manage_id')
                    ->where('cmp_approve_level1.progress_type', '=', 'Approval By Manager')
                    ->whereRaw('cmp_approve_level1.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By Manager"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })
            ->leftJoin('change_manage_progress as cmp_approve_level2', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level2.change_manage_id')
                    ->where('cmp_approve_level2.progress_type', '=', 'Approval By GM')
                    ->whereRaw('cmp_approve_level2.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By GM"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })
            ->leftJoin('approvals as app1', function (JoinClause $join) {
                $join->on('cmp_approve_level1.id', '=', 'app1.approvalable_id')
                    ->where('app1.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->leftJoin('approvals as app2', function (JoinClause $join) {
                $join->on('cmp_approve_level2.id', '=', 'app2.approvalable_id')
                    ->where('app2.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })


            ->with([
                'changeManageProgress' => function ($query) {
                    $query
                        ->with('attachments', 'approval')
                        ->orderBy('id', 'desc');
                },
                'lastProgress' => function ($query) {
                    $query->with('approval');
                },
                'engineer' => function ($query) {
                    $query->with(
                        [
                            'karyawan' => function ($query) {
                                $query->with('jabatan');
                            }
                        ]
                    );
                },
                'approval1' => function ($query) {
                    $query->with(
                        [
                            'karyawan' => function ($query) {
                                $query->with('jabatan');
                            }
                        ]
                    );
                },
                'approval2' => function ($query) {
                    $query->with(
                        [
                            'karyawan' => function ($query) {
                                $query->with('jabatan');
                            }
                        ]
                    );
                },
                'attachments', 'engineers', 'location',
            ])
            ->selectRaw("change_manages.*, user_app1.full_name as approval1_name, user_app1.email as approval1_email, user_app2.full_name as approval2_name, user_app2.email as approval2_email, crt.full_name as creator_name, en.email as engineer_email, en.full_name as engineer_name, lc.name as location_name, app1.status_approval as status_approval1, app2.status_approval as status_approval2, en.id_account as account0_id, user_app1.id_account as account1_id, user_app2.id_account as account2_id, CONCAT('Manager ', department_manager.name) as jabatan_manager")
            ->find($id);

        return $changes;
    }

    public function timeProgress(int $id)
    {
        $ttr = $this->model
            ->leftJoin(DB::raw('(SELECT id, change_manage_id, inputed_date FROM change_manage_progress WHERE progress_type = "Submit" AND change_manage_id = ? ORDER BY id DESC LIMIT 1) as cmp_submit'), function ($join) use ($id) {
                $join->setBindings([$id])->on('change_manages.id', '=', 'cmp_submit.change_manage_id');
            })
            ->leftJoin(DB::raw('(SELECT cmp.id, cmp.change_manage_id, cmp.inputed_date FROM change_manage_progress as cmp LEFT JOIN (SELECT id, approvalable_id, approvalable_type, status_approval FROM approvals WHERE approvalable_type = "App\\\\Models\\\\ChangeManageProgress") as app ON cmp.id = app.approvalable_id WHERE progress_type = "Approval By Manager" AND change_manage_id = ? AND app.status_approval = 1 ORDER BY cmp.id DESC LIMIT 1) as cmp_approval1'), function ($join) use ($id) {
                $join->setBindings([$id])->on('change_manages.id', '=', 'cmp_approval1.change_manage_id');
            })
            ->leftJoin(DB::raw('(SELECT cmp.id, cmp.change_manage_id, cmp.inputed_date FROM change_manage_progress as cmp LEFT JOIN (SELECT id, approvalable_id, approvalable_type, status_approval FROM approvals WHERE approvalable_type = "App\\\\Models\\\\ChangeManageProgress") as app ON cmp.id = app.approvalable_id WHERE progress_type = "Approval By GM" AND change_manage_id = ? AND app.status_approval = 1 ORDER BY cmp.id DESC LIMIT 1) as cmp_approval2'), function ($join) use ($id) {
                $join->setBindings([$id])->on('change_manages.id', '=', 'cmp_approval2.change_manage_id');
            })
            ->leftJoin(DB::raw('(SELECT id, change_manage_id, inputed_date FROM change_manage_progress WHERE progress_type = "Engineer Troubleshoot" AND change_manage_id = ? ORDER BY id DESC LIMIT 1) as cmp_eng_trouble'), function ($join) use ($id) {
                $join->setBindings([$id])->on('change_manages.id', '=', 'cmp_eng_trouble.change_manage_id');
            })
            ->leftJoin(DB::raw('(SELECT id, change_manage_id, inputed_date FROM change_manage_progress WHERE progress_type = "Closed" AND change_manage_id = ? ORDER BY id DESC LIMIT 1) as cmp_closed'), function ($join) use ($id) {
                $join->setBindings([$id])->on('change_manages.id', '=', 'cmp_closed.change_manage_id');
            })
            ->selectRaw('change_manages.created_date, cmp_submit.inputed_date as cmp_submit, cmp_approval1.inputed_date as cmp_approval1, cmp_approval2.inputed_date as cmp_approval2, cmp_eng_trouble.inputed_date as cmp_eng_trouble, cmp_closed.inputed_date as cmp_closed')
            ->groupBy('change_manages.id')
            ->where('change_manages.id', $id)
            ->get();

        $pivot = [];
        foreach ($ttr[0]->toArray() as $key => $value) {
            if ($value != null)
                $pivot[$key] = $value;
        }

        $pivotKeys = array_keys($pivot);
        $pivotValues = array_values($pivot);

        $timeDiff = array_map(function ($value, $key) use ($pivotValues) {
            if ($key === 0) {
                return 0;
            }
            $diff = strtotime($value) - strtotime($pivotValues[$key - 1]);

            $days = floor($diff / 86400);
            $diff = $diff % 86400;

            // Menghitung jam
            $hours = floor($diff / 3600);
            $diff = $diff % 3600;

            // Menghitung menit
            $minutes = floor($diff / 60);
            $seconds = $diff % 60;

            $time = ($days != 0 ? $days . " Hari" : '') . ($hours != 0 ? ($days != 0 ? ", " : '') . $hours . " Jam" : '') . ($minutes != 0 ? ($hours != 0 ? ", " : ($days != 0 ? ', ' : '')) . $minutes . " Menit" : '') . ($seconds != 0 ? ($minutes != 0 ? ", " : ($hours != 0 ? ', ' : ($days != 0 ? ', ' : ''))) . $seconds . " Detik" : '');

            return $time ? $time : 0;
        }, $pivotValues, array_keys($pivotValues));

        $recapTime = array_combine(array_map(function ($key) {
            return str_replace('cmp_', 'diff_', $key);
        }, $pivotKeys), $timeDiff);
        array_shift($recapTime);

        return $recapTime;
    }

    public function findById(int $id): object
    {
        $changes = $this->model->with('location', 'approval1', 'approval2')->find($id);
        return $changes;
    }

    public function lastData()
    {
        $latest = $this->model->orderBy('id', 'desc')->first();
        return $latest;
    }

    public function findByNomor($nomor)
    {
        $changes = $this->model
            ->where('change_manages.nomor_changes', $nomor)
            ->first();
        return $changes;
    }

    public function verifCR(object $request, int $id)
    {
        $changes = $this->model->find($id);

        if ($request->verif == 'manager') :
            $verif = $changes->update([
                'status_approval1'       => $request->status ?? null,
                'reason_reject1'      => $request->reject_content ?? null,
            ]);
        else :
            $verif = $changes->update([
                'status_approval2'       => $request->status ?? null,
                'reason_reject2'      => $request->reject_content ?? null,
            ]);
        endif;

        return $verif;
    }

    public function exportCR($request = null): object
    {
        $changes = $this->model->query()
            ->selectRaw('change_manages.nomor_changes, change_manages.title, change_manages.status, change_manages.type, change_manages.reference, change_manages.pic_telkomsat, change_manages.pic_nontelkomsat, change_manages.agenda, change_manages.datetime_action, change_manages.content, change_manages.created_date, change_manages.last_updated_date, change_manages.closed_date, us.full_name as engineer_name, us.email as engineer_email, user_app1.full_name as approval1_name, user_app1.email as approval1_email, user_app2.full_name as approval2_name, user_app2.email as approval2_email, crt.full_name as creator_name, lc.name as location_name')
            ->where('is_deleted', 0)
            ->leftJoin('engineer_assignment_changes as aec', function ($join) {
                $join->on('change_manages.id', '=', 'aec.change_manage_id')
                    ->leftJoin('engineers as eng', function ($join) {
                        $join->on('aec.id', '=', 'eng.engineer_assignment_changes_id')
                            ->leftJoin('users as us', 'eng.user_id', '=', 'us.id');
                    });
            })
            ->leftJoin('users as user_app1', function ($join) {
                $join->on('change_manages.approval_level1_id', '=', 'user_app1.id');
            })
            ->leftJoin('users as user_app2', function ($join) {
                $join->on('change_manages.approval_level2_id', '=', 'user_app2.id');
            })
            ->leftJoin('users as crt', function ($join) {
                $join->on('change_manages.creator_id', '=', 'crt.id');
            })
            ->leftJoin('locations as lc', function ($join) {
                $join->on('change_manages.location_id', '=', 'lc.id');
            })
            ->leftJoin('change_manage_progress as cmp', 'change_manages.last_progress_id', '=', 'cmp.id')
            ->leftJoin('change_manage_progress as cmp_approve_level1', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level1.change_manage_id')
                    ->where('cmp_approve_level1.progress_type', '=', 'Approval By Manager')
                    ->whereRaw('cmp_approve_level1.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By Manager"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })
            ->leftJoin('change_manage_progress as cmp_approve_level2', function ($join) {
                $join->on('change_manages.id', '=', 'cmp_approve_level2.change_manage_id')
                    ->where('cmp_approve_level2.progress_type', '=', 'Approval By GM')
                    ->whereRaw('cmp_approve_level2.id = (
                        SELECT id FROM change_manage_progress
                        WHERE change_manage_id = change_manages.id
                        AND progress_type = "Approval By GM"
                        ORDER BY id DESC
                        LIMIT 1
                    )');
            })

            ->leftJoin('approvals as app1', function (JoinClause $join) {
                $join->on('cmp_approve_level1.id', '=', 'app1.approvalable_id')
                    ->where('app1.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->leftJoin('approvals as app2', function (JoinClause $join) {
                $join->on('cmp_approve_level2.id', '=', 'app2.approvalable_id')
                    ->where('app2.approvalable_type', '=', 'App\Models\ChangeManageProgress');
            })
            ->groupBy('change_manages.id')
            ->when($request->filled('nomor_changes'), function ($query) use ($request) {
                $query->where('nomor_changes', '=', $request->input('nomor_changes'));
            })
            ->when($request->filled('title'), function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', 'LIKE', '%' . json_encode($request->input('type')) . '%');
            })
            ->when($request->filled('priority'), function ($query) use ($request) {
                $query->where('priority', '=', $request->input('priority'));
            })
            ->when($request->filled('datetime_action'), function ($query) use ($request) {
                $query->whereDate('datetime_action', '=', Carbon::parse($request->input('datetime_action'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('pic_telkomsat'), function ($query) use ($request) {
                $query->where('pic_telkomsat', 'LIKE', '%' . $request->input('pic_telkomsat') . '%');
            })
            ->when($request->filled('pic_nontelkomsat'), function ($query) use ($request) {
                $query->where('pic_nontelkomsat', 'LIKE', '%' . $request->input('pic_nontelkomsat') . '%');
            })
            ->when($request->filled('agenda'), function ($query) use ($request) {
                $query->where('agenda', 'LIKE', '%' . $request->input('agenda') . '%');
            })
            ->when($request->filled('approval_level1_id'), function ($query) use ($request) {
                $query->where('approval_level1_id', '=', $request->input('approval_level1_id'));
            })
            ->when($request->filled('approval_level2_id'), function ($query) use ($request) {
                $query->where('approval_level2_id', '=', $request->input('approval_level2_id'));
            })
            ->when($request->filled('status_approval1'), function ($query) use ($request) {
                $query->where('status_approval1', '=', $request->input('status_approval1'));
            })
            ->when($request->filled('status_approval2'), function ($query) use ($request) {
                $query->where('status_approval2', '=', $request->input('status_approval2'));
            })
            ->when($request->filled('last_updated_date'), function ($query) use ($request) {
                $query->whereDate('last_updated_date', '=', Carbon::parse($request->input('last_updated_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('closed_date'), function ($query) use ($request) {
                $query->whereDate('closed_date', '=', Carbon::parse($request->input('closed_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('location_id'), function ($query) use ($request) {
                $query->where('location_id', '=', $request->input('location_id'));
            })
            ->when($request->filled('creator_id'), function ($query) use ($request) {
                $query->where('creator_id', '=', $request->input('creator_id'));
            })
            ->when($request->filled('created_date'), function ($query) use ($request) {
                $query->whereDate('created_date', '=', Carbon::parse($request->input('created_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $request_status = $request->input('status');
                if ($request_status == 'draft')
                    $query->where('change_manages.is_draft', 1);
                if ($request_status == 'completed')
                    $query->where('change_manages.status', 'Closed');
                if ($request_status == 'verification')
                    $query
                        ->where('is_draft', 0)
                        ->where(function ($query) {
                            $query->where('app1.status_approval', 2)
                                ->where('change_manages.is_deleted', 0)
                                ->orWhereNull('app1.status_approval');
                        })
                        ->orWhere(function ($query) {
                            $query->where('app2.status_approval', 2)
                                ->where('change_manages.is_deleted', 0)
                                ->whereIn('cmp.progress_type', ['Edit', 'Approval By GM']);
                        });
                if ($request_status == 'approval')
                    $query
                        ->where(function ($query) {
                            $query->where('app1.status_approval', 1)
                                ->where('change_manages.is_deleted', 0)
                                ->where(function ($query) {
                                    $query->whereNull('app2.status_approval')
                                        ->orWhere(function ($query) {
                                            $query->where('app2.status_approval', 2)
                                                ->where('change_manages.is_deleted', 0)
                                                ->where('cmp.progress_type', 'Approval By Manager');
                                        });
                                });
                        })->where('change_manages.status', 'Open');
            });

        return $changes;
    }

    public function updateByColumn($request, $id)
    {
        $changes = $this->model->find($id);
        $changes->update($request);

        return $changes;
    }

    public function createProgress($request)
    {
        return $this->changeManageProgress->create($request);
    }

    public function createChange($data_request, $request): array
    {

        $changes = null;
        if (!$request->id) {
            unset($data_request['create_or_update']['images_removed']);
            $changes = $this->model->create($data_request['create_or_update']);
            if ($data_request['create_progress'] ?? null) $progress = $changes->changeManageProgress()->create($data_request['create_progress']);
        } else {
            $changes = $this->model->find($request->id);
            if (!$changes->is_draft || $request->button_type == 'submit') {
                if ($data_request['create_progress'] ?? null) $progress = $changes->changeManageProgress()->create($data_request['create_progress']);
                $data_request['create_or_update'] += ['last_progress_id' => $progress->id];
            }

            foreach (explode(',', $data_request['create_or_update']['images_removed']) as $id) {
                $changes->attachments()->where('id', $id)->delete();
            }
            unset($data_request['create_or_update']['images_removed']);
            $changes->update($data_request['create_or_update']);
        }

        (object)$data_request['trouble_ticket_id_attachment'] = $changes->id;
        if ($request->hasFile('image_proof')) :
            $request['change_manage_id_attachment'] = $changes->id;
            $request['inputer_id_attachment'] = $request->creator_id;
            $attachments = $this->verifyAndUpload($request, 'image_proof', 'image_proof');
        endif;

        return ['changes' => $changes ?? null, 'attachments' => $attachments ?? []];
    }

    public function updateProgress($request, $id): object
    {
        $changes = $this->model->find($id);

        $progress = (object)[];
        $progress_type = $request['progress_type'];

        switch ($progress_type) {
            case "Approval By Manager" || "Approval By GM":
                $progress = $changes->changeManageProgress()->create($request['update_progress_approval']);
                $progress->approval()->create($request['update_approval']);
                $changes->update($request['update_changes'] += ['last_progress_id' => $progress->id]);
                break;
        }

        if ($request['request']->hasFile('image_proof')) :
            $request['request']['change_manage_progress_id_attachment'] = $progress->id;
            $request['request']['inputer_id_attachment'] = auth()->id();
            $this->verifyAndUpload($request['request'], 'image_proof', 'image_proof');
        endif;

        return $changes;
    }

    public function getDatacomLocation(): object
    {
        $location = DB::connection('mysql2')->table('datacom_location')->select('*')->get();
        return $location;
    }

    public function getUserCR(): object
    {
        $user = DB::connection('mysql2')->table('user')->select('id', 'n_akun')->get();
        return $user;
    }

    public function deleteComment($id)
    {
        $data = $this->comment->find($id);

        if (!$data) {
            throw new BadRequestException("Comment tidak ditemukan!");
        }

        $data->delete();

        return true;
    }

    public function deleteChanges($request, $id)
    {
        $changes = $this->model->find($id);
        if (!$changes) throw new Exception("Changes tidak ditemukan!");

        $data_log = [
            'reason' => $request->reason,
            'deletable_id' => $id,
            'deleter_id' => auth()->id()
        ];

        $changes->update(['is_deleted' => true]);
        $changes->deleteLogs()->create($data_log);

        return;
    }
}
