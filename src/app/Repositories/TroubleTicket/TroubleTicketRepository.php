<?php

namespace App\Repositories\TroubleTicket;

use App\Models\DeleteLog;
use App\Models\MantoolsDatacom;
use App\Models\Notification;
use Carbon\Carbon;
use App\Traits\Action;
use App\Traits\ImageTrait;
use App\Models\TroubleTicket;
use App\Models\TroubleTicketProgress;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TroubleTicketRepository implements TroubleTicketRepositoryInterface
{
    use ImageTrait;
    use Action;

    protected $model;
    protected static $modelStatic;
    protected $troubleTicketProgress;
    protected $mantoolsDatacom;
    protected $notification;
    protected $deleteLog;

    public function __construct(TroubleTicket $model, TroubleTicketProgress $troubleTicketProgress, MantoolsDatacom $mantoolsDatacom, Notification $notification, DeleteLog $deleteLog)
    {
        $this->model = $model;
        self::$modelStatic = $model;
        $this->troubleTicketProgress = $troubleTicketProgress;
        $this->mantoolsDatacom = $mantoolsDatacom;
        $this->notification = $notification;
        $this->deleteLog = $deleteLog;
    }

    public function model()
    {
        return $this->model;
    }


    // Get All Ticket Process
    public function getTicketAll(object $request, $department)
    {
        $data = $this->model->selectRaw("trouble_tickets.id, nomor_ticket, priority, type, problem_type, dp.name as department_name, trouble_tickets.department_id, sv.name as service_name, subject, update_type, us.full_name as progress_inputer, trouble_tickets.created_date, ctr.full_name as creator_name, ti.source_info as source_info_trouble, lc.name as location_name, trouble_tickets.technical_closed_date, trouble_tickets.status, timediff(now(), trouble_tickets.created_date) as time_diff_now, trouble_tickets.last_updated_date, ttp.update_type as last_progress, resume_gm, resume_cto, step")
            ->leftJoin('trouble_ticket_progress as ttp', 'trouble_tickets.last_progress_id', '=', 'ttp.id')
            ->leftJoin('users as ctr', 'trouble_tickets.creator_id', '=', 'ctr.id')
            ->leftJoin('users as us', 'ttp.inputer_id', '=', 'us.id')
            ->leftJoin('ticket_infos as ti', 'trouble_tickets.id', '=', 'ti.trouble_ticket_id')
            ->leftJoin('departments as dp', 'trouble_tickets.department_id', '=', 'dp.id')
            ->leftJoin('services as sv', 'trouble_tickets.service_id', '=', 'sv.id')
            ->leftJoin('locations as lc', 'trouble_tickets.event_location_id', '=', 'lc.id')
            ->when($request->filled('request.created_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.created_date', Carbon::parse($request->input('request.created_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.last_updated_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.last_updated_date', Carbon::parse($request->input('request.last_updated_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('request.technical_closed_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.technical_closed_date', Carbon::parse($request->input('request.technical_closed_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->where('is_deleted', 0);
        if (!empty($request->input('request.status')) && $request->input('request.status') != 'all') :
            $data->whereIn('trouble_tickets.status', ucwords($request->input('request.status')) === 'Open' ? ['Open', 'Waiting Close'] : [ucwords($request->input('request.status'))]);
        endif;

        // Filter department (by access)
        if ($department != null) {
            $data->where(function ($query) use ($department) {
                $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                    $query->whereIn('departments.id', $department)
                        ->where('trouble_ticket_progress.update_type', 'Dispatch')
                        ->orderBy('trouble_ticket_progress.id', 'desc')
                        ->limit(1);
                })
                    ->orWhereHas('department', function ($query) use ($department) {
                        $query->whereIn('departments.id', $department);
                    });
            });
        }

        // Filter others
        if (!empty($request->input('request.nomor_ticket')))
            $data->where('nomor_ticket', $request->input('request.nomor_ticket'));
        if (!empty($request->input('request.priority')))
            $data->where('priority', $request->input('request.priority'));
        if (!empty($request->input('request.type')))
            $data->where('type', $request->input('request.type'));
        if (!empty($request->input('request.department')))
            $data->where('dp.id', $request->input('request.department'));
        if (!empty($request->input('request.service')))
            $data->where('sv.id', $request->input('request.service'));
        if (!empty($request->input('request.subject')))
            $data->where('subject', $request->input('request.subject'));
        if (!empty($request->input('request.update_type')))
            $data->where('ttp.update_type', $request->input('request.update_type'));
        if (!empty($request->input('request.progress_inputer')))
            $data->where('us.id', $request->input('request.progress_inputer'));
        if (!empty($request->input('request.creator')))
            $data->where('ctr.id', $request->input('request.creator'));
        if (!empty($request->input('request.source_info_trouble')))
            $data->where('ti.source_info', 'LIKE', '%' . $request->input('request.source_info_trouble') . '%');
        if (!empty($request->input('request.event_location_id')))
            $data->where('event_location_id', $request->input('request.event_location_id'));

        return $data;
    }

    // Export ticket process
    public function exportTicket(object $request, $department): object
    {

        // Subquery get total pending time of ticket
        $subquery = DB::table('trouble_ticket_progress')
            ->select('trouble_ticket_id', DB::raw('SUM(TIMESTAMPDIFF(SECOND, created_at, closed_at)) as pending_time'))
            ->leftJoin('pendings', 'trouble_ticket_progress.id', '=', 'pendings.trouble_ticket_progress_id')
            ->where('pendings.pending_by', 'By Customer')
            ->groupBy('trouble_ticket_id');

        // Get full spec ticket
        $data = $this->model->query()->select('nomor_ticket', 'last_updated_date', 'priority', 'type', 'dp.name as department_name', 'sv.name as service_name', 'subject', 'update_type', 'us.full_name as progress_inputer', 'created_date', 'ctr.full_name as creator_name', 'ti.source_info as source_info_trouble', 'trouble_tickets.status', 'problem', 'kt.information as solution', 'tc.evaluation', 'detail_info', 'ti.name as ticket_info_name', 'ti.number_phone', 'ti.email', 'trouble_tickets.updated_date', 'trouble_tickets.technical_closed_date', 'trouble_tickets.closed_date', 'lc.name as location_name', 'event_datetime', DB::raw('TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND) as time_duration_recovery, TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date) as time_duration_response, IF(trouble_tickets.is_visited = 1, "Iya", "Tidak") as visit_location'))
            ->leftJoin('trouble_ticket_progress as ttp', 'trouble_tickets.last_progress_id', '=', 'ttp.id')
            ->leftJoin('users as ctr', 'trouble_tickets.creator_id', '=', 'ctr.id')
            ->leftJoin('users as us', 'ttp.inputer_id', '=', 'us.id')
            ->leftJoin('ticket_infos as ti', 'trouble_tickets.id', '=', 'ti.trouble_ticket_id')
            ->leftJoin('departments as dp', 'trouble_tickets.department_id', '=', 'dp.id')
            ->leftJoin('services as sv', 'trouble_tickets.service_id', '=', 'sv.id')
            ->leftJoin('catalogs as kt', 'trouble_tickets.id', '=', 'kt.trouble_ticket_id')
            ->leftJoinSub($subquery, 'p', function ($join) {
                $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
            })
            ->leftJoin('locations as lc', 'trouble_tickets.event_location_id', '=', 'lc.id')
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id FROM trouble_ticket_progress WHERE update_type = "Technical Close" ORDER BY id DESC LIMIT 1) as ttp_tech_close'), function ($join) {
                $join->on('trouble_tickets.id', '=', 'ttp_tech_close.trouble_ticket_id')
                    ->leftJoin('technical_closes as tc', 'ttp_tech_close.id', '=', 'tc.trouble_ticket_progress_id');
            })
            ->when($request->filled('created_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.created_date', Carbon::parse($request->input('created_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('last_updated_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.last_updated_date', Carbon::parse($request->input('last_updated_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->when($request->filled('technical_closed_date'), function ($query) use ($request) {
                $query->whereDate('trouble_tickets.technical_closed_date', Carbon::parse($request->input('technical_closed_date'))->isoFormat('YYYY-MM-DD'));
            })
            ->where('is_deleted', 0);
        if (!empty($request->input('status')) && $request->input('status') != 'all') :
            $data->whereIn('trouble_tickets.status', ucwords($request->input('request.status')) === 'Open' ? ['Open', 'Waiting Close'] : [ucwords($request->input('request.status')) === 'Open' ? ['Open', 'Waiting Close'] : [ucwords($request->input('status'))]]);
        endif;

        // Get ticket by department
        if ($department != null) {
            $data->where(function ($query) use ($department) {
                $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                    $query->whereIn('departments.id', $department)
                        ->where('trouble_ticket_progress.update_type', 'Dispatch')
                        ->orderBy('trouble_ticket_progress.id', 'desc')
                        ->limit(1);
                })
                    ->orWhereHas('department', function ($query) use ($department) {
                        $query->whereIn('departments.id', $department);
                    });
            });
        }

        if (!empty($request->input('nomor_ticket')))
            $data->where('nomor_ticket', $request->input('nomor_ticket'));
        if (!empty($request->input('priority')))
            $data->where('priority', $request->input('priority'));
        if (!empty($request->input('type')))
            $data->where('type', $request->input('type'));
        if (!empty($request->input('department')))
            $data->where('dp.id', $request->input('department'));
        if (!empty($request->input('service')))
            $data->where('sv.id', $request->input('service'));
        if (!empty($request->input('subject')))
            $data->where('subject', $request->input('subject'));
        if (!empty($request->input('update_type')))
            $data->where('ttp.update_type', $request->input('update_type'));
        if (!empty($request->input('progress_inputer')))
            $data->where('us.id', $request->input('progress_inputer'));
        if (!empty($request->input('creator')))
            $data->where('ctr.id', $request->input('creator'));
        if (!empty($request->input('source_info_trouble')))
            $data->where('ti.source_info', 'LIKE', '%' . $request->input('source_info_trouble') . '%');
        if (!empty($request->input('event_location_id')))
            $data->where('event_location_id', $request->input('event_location_id'));

        return $data;
    }

    // Get detail ticket for detail page
    public function detailTicket(int $id): object
    {
        $ticket = $this->model
            ->with([
                'troubleTicketProgress' => function ($query) {
                    $query
                        ->with('attachments', 'editor')
                        ->orderBy('id', 'desc');
                },
                'mantoolsDatacom' => function ($query) {
                    $query->selectRaw('id, CONCAT(customer, " - ", name, " - ", location) as data_backhaul, datacom_perangkat_id, datacom_metro_e_id, datacom_vpn_ip_id, datacom_ip_transit_id, datacom_astinet_id');
                },
            ])
            ->find($id);
        if (empty($ticket)) {
            throw new BadRequestException("Data tidak ditemukan!");
        }
        return $ticket;
    }

    // Get time for each ticket type
    public function timeProgress(int $id)
    {
        $ttr = $this->model
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Diagnose" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_diagnosa'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_diagnosa.trouble_ticket_id');
            })
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Engineer Assignment" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_eng_assign'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_eng_assign.trouble_ticket_id');
            })
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Engineer Troubleshoot" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_eng_trouble'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_eng_trouble.trouble_ticket_id');
            })
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Technical Close" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_tech_close'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_tech_close.trouble_ticket_id');
            })
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Monitoring" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_monitor'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_monitor.trouble_ticket_id');
            })
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Closed" AND trouble_ticket_id = ? ORDER BY id DESC LIMIT 1) as ttp_closed'), function ($join) use ($id) {
                $join->setBindings([$id])->on('trouble_tickets.id', '=', 'ttp_closed.trouble_ticket_id');
            })
            ->selectRaw('trouble_tickets.created_date, ttp_diagnosa.inputed_date as ttp_diagnosa, ttp_eng_assign.inputed_date as ttp_eng_assign, ttp_eng_trouble.inputed_date as ttp_eng_trouble, ttp_tech_close.inputed_date as ttp_tech_close, ttp_monitor.inputed_date as ttp_monitor, ttp_closed.inputed_date as ttp_closed')
            ->groupBy('trouble_tickets.id')
            ->where('trouble_tickets.id', $id)
            ->get();


        return $ttr[0];
    }

    // Get info less than detailTicket
    public function findById(int $id): object
    {
        $ticket = $this->model
            ->with([
                'ttpEngineerAssignment' => function ($query) {
                    $query
                        ->with(['engineerUser' => function ($query) {
                            $query->select('users.id', 'users.full_name');
                        }])
                        ->where('update_type', 'Engineer Assignment')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                },
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
                    $query->select('departments.id', 'departments.name');
                },
                'service' => function ($query) {
                    $query->select('services.id', 'services.name');
                },
                'device' => function ($query) {
                    $query->select('devices.id', 'devices.name');
                },
                'eventLocation' => function ($query) {
                    $query->select('locations.id', 'locations.name');
                },
                'lastProgress' => function ($query) {
                    $query->select('trouble_ticket_progress.id', 'trouble_ticket_progress.update_type', 'trouble_ticket_progress.content');
                },
                'troubleTicketProgress' => function ($query) {
                    $query
                        ->with('attachments', 'user', 'engineerAssignment.engineer')
                        ->orderBy('id', 'asc');
                },
                'ticketInfo',
                'catalogs'
            ])
            ->leftJoin('ticket_infos as ti', 'trouble_tickets.id', '=', 'ti.trouble_ticket_id')
            ->selectRaw("trouble_tickets.*, ti.source_info, ti.email as customer_email, TIME_FORMAT(timediff(now(), trouble_tickets.created_date), '%H:%i') as time_diff_now")
            ->find($id);
        return $ticket;
    }

    // Find the ticket progress
    public function findTicketProgress($id)
    {
        return $this->troubleTicketProgress->find($id);
    }

    // Get ticket by ticket number
    public function findByNomor(string $nomor)
    {
        $ticket = $this->model
            ->with([
                'ttpEngineerAssignment' => function ($query) {
                    $query
                        ->with(['engineerUser' => function ($query) {
                            $query->select('users.id', 'users.full_name');
                        }])
                        ->where('update_type', 'Engineer Assignment')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                },
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
                    $query->select('departments.id', 'departments.name');
                },
                'service' => function ($query) {
                    $query->select('services.id', 'services.name');
                },
                'eventLocation' => function ($query) {
                    $query->select('locations.id', 'locations.name');
                },
                'lastProgress' => function ($query) {
                    $query->select('trouble_ticket_progress.id', 'trouble_ticket_progress.update_type', 'trouble_ticket_progress.content');
                },
            ])
            ->selectRaw("trouble_tickets.*, TIME_FORMAT(timediff(now(), trouble_tickets.created_date), '%H:%i') as time_diff_now")
            ->where('trouble_tickets.nomor_ticket', $nomor)
            ->first();
        return $ticket;
    }

    public function findByIdDetail(int $id): object
    {
        $ticket = $this->model->selectRaw('nomor_ticket, subject, priority, type, problem_type, event_datetime, lc.name as location_name, dp.name as department_name, sv.name as service_name, ti.source_info as source_info_trouble, trouble_tickets.status, ti.name, ti.number_phone, ti.email, dsp.department_to_id as department_dispatch_id, dp.id as department_id, resume_gm, resume_cto')
            ->leftJoin('trouble_ticket_progress as ttp', 'trouble_tickets.last_progress_id', '=', 'ttp.id')
            ->leftJoin('ticket_infos as ti', 'trouble_tickets.id', '=', 'ti.trouble_ticket_id')
            ->leftJoin('services as sv', 'trouble_tickets.service_id', '=', 'sv.id')
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id, inputed_date FROM trouble_ticket_progress WHERE update_type = "Dispatch" ORDER BY id DESC LIMIT 1) as ttp_dispatch'), function ($join) {
                $join->on('trouble_tickets.id', '=', 'ttp_dispatch.trouble_ticket_id')
                    ->join('dispatches as dsp', 'ttp_dispatch.id', '=', 'dsp.trouble_ticket_progress_id');
            })
            ->leftJoin('departments as dp', 'trouble_tickets.department_id', '=', 'dp.id')
            ->leftJoin('locations as lc', 'trouble_tickets.event_location_id', '=', 'lc.id')
            ->find($id);

        return $ticket;
    }

    // Update status resume send
    public function updateStatusResume($request, $id)
    {
        $data = $this->model->where('id', $id)->update($request);
        return $data;
    }

    // Save log resume
    public function saveResume($request, $id)
    {
        $ticket = $this->model->find($id);
        $ticket->resumeEmail()->create($request);
    }

    // Get last of ticket data
    public function lastData()
    {
        $latest = $this->model->orderBy('id', 'desc')->first();
        return $latest;
    }

    public function createTicket(object $request, string $nomor_ticket = ''): array
    {
        // Create new ticket if step 1
        if ($request->step == 1) :
            $ticket = $this->model->create([
                'creator_id' => $request->creator_id,
                'nomor_ticket' => $nomor_ticket,
                'type' => $request->type,
                'category' => $request->category,
                'subject' => $request->subject,
                'status' => $request->status ?? 'Open',
                'last_updated_date' => null,
                'closed_date' => null,
                'technical_closed_date' => null,
            ]);

            $ticket->ticketInfo()->create([
                'name' => $request->reporter_name,
            ]);

            return ['ticket' => $ticket];
        else :
            // Update detail info of ticket after create step 1
            $ticket = $this->model->find($request->id);
            $ticket->update([
                'priority' => $request->priority,
                'problem_type' => $request->problem_type,
                'type' => $request->type,
                'category' => $request->category,
                'service_id' => $request->service_id,
                'subject' => $request->subject,
                'problem' => $request->problem,
                'event_datetime' => date('Y-m-d H:i:s', strtotime($request->event_date . " " . $request->event_time)),
                'event_location_id' => $request->event_location_id,
                'step' => 2,
                'status' => $request->status ?? 'Open',
            ]);

            $ticket->ticketInfo()->update([
                'department_id' => $request->reporter_department,
                'source_info' => $request->source_info_trouble,
                'detail_info' => $request->detail_info,
                'name' => $request->reporter_name,
                'email' => $request->reporter_email,
                'number_phone' => $request->reporter_phone,
                'nik' => $request->reporter_nik,
            ]);

            if ($request->hasFile('image_proof')) :
                $request['trouble_ticket_id_attachment'] = $ticket->id;
                $request['inputer_id_attachment'] = $request->creator_id;
                $attachments = $this->verifyAndUpload($request, 'image_proof', 'image_proof');
            endif;

            return ['ticket' => $ticket, 'attachments' => $attachments ?? []];

        endif;
    }
    // Update progress every had new activity
    public function updateProgress($request, $id): object
    {
        $ticket = $this->model->find($id);
        // Create new progress every new activity
        $progress = $ticket->troubleTicketProgress()->create([
            'inputer_id' => $request->inputer_id,
            'content' => $request->content,
            'inputed_date' => date('Y-m-d H:i:s', strtotime($request->inputed_date . " " . $request->inputed_time)) ?? now(),
            'update_type' => $request->update_type,
        ]);

        // Get image proof from input
        if ($request->hasFile('image_proof')) :
            $request['trouble_ticket_progress_id_attachment'] = $progress->id;
            $request['inputer_id_attachment'] = $request->inputer_id;
            $this->verifyAndUpload($request, 'image_proof', 'image_proof');
        endif;

        $progress_id = $progress->id;
        $type = $request->update_type;

        switch ($type) {
            case "Diagnose":
                $this->updateTicket($request, $type, $id, $progress_id);
                break;
            case "Dispatch":
                $progress->dispatch()->create([
                    'department_from_id' => $request->department_from_id,
                    'department_to_id' => $request->department_to_id,
                ]);
                $this->updateTicket($request, $type, $id, $progress_id);
                break;
            case "Pending":
                $duration = strtotime(date('Y-m-d H:i:s', strtotime($request->duration_date . " " . $request->duration_time))) - strtotime(now());
                $progress->pending()->create([
                    'pending_by' => $request->pending_by,
                    'duration' => $duration,
                    'reason' => $request->reason,
                ]);
                $this->updateTicket($request, $type, $id, $progress_id);
                break;
            case "Engineer Assignment":
                $engineer_assignment = $progress->engineerAssignment()->create([]);
                foreach ((array)$request['engineer_assignment_id'] as $key => $engineer_assignment_id) {
                    $data_id = [
                        'engineer_assignment_id' => $engineer_assignment->id,
                        'user_id' => $engineer_assignment_id,
                    ];
                    $this->createEngineer($data_id);
                }
                $this->updateTicket($request, $type, $id, $progress_id);
                break;
            case "Technical Close":
                $progress->technicalClose()->create([
                    'close_datetime' => date('Y-m-d H:i:s', strtotime($request->inputed_date . " " . $request->inputed_time)) ?? now(),
                    'evaluation' => $request->evaluation,
                    'rfo' => $request->rfo,
                ]);
                $this->updateTicket($request, $type, $id, $progress_id);
                if ($ticket->type === 'Incident TTR') :
                    $ticket->catalog()->create([
                        'title' => $ticket->subject,
                        'information' => $request->solution
                    ]);
                endif;
                break;
            default:
                $this->updateTicket($request, $type, $id, $progress_id);
                break;
        }
        return $progress;
    }

    // Close from pending with add information about that
    public function closePending($id)
    {
        $ticket = $this->model->find($id);
        $pending = $ticket->troubleTicketProgress()
            ->where('trouble_ticket_progress.update_type', 'Pending')
            ->orderBy('trouble_ticket_progress.id', 'desc')
            ->first()->pending();
        $pending->update([
            'closed_at' => now()
        ]);
    }

    // Sub function of update progress
    public function updateTicket($request, $type, $id, $progress_id)
    {
        $ticket = $this->model->find($id);

        if ($ticket->status == 'Pending' && $type != 'Pending') :
            $this->closePending($id);
        endif;

        $array = [
            'Diagnose' => [
                'department_id' => $request->department_id,
                'device_id' => $request->device_id,
                'service_id' => $request->service_id,
                'last_progress_id' => $progress_id,
                'status' => 'Open',
                'last_updated_date' => now(),
                'mantools_datacom_id' => $request->filled('mantools_datacom_id') ? $request->mantools_datacom_id : null
            ],
            'Engineer Assignment' => [
                'status' => 'Open',
                'last_progress_id' => $progress_id,
                'last_updated_date' => now(),
                'close_estimation' => date('Y-m-d H:i:s', strtotime($request->close_estimation_date . " " . $request->close_estimation_time)),
            ],
            'All' => [
                'status' => 'Open',
                'last_progress_id' => $progress_id,
                'last_updated_date' => now(),
            ],
            'Technical Close' => [
                'status' => 'Open',
                'is_visited' => $request->is_visited,
                'resume_id' => $request->resume_id,
                'last_progress_id' => $progress_id,
                'technical_closed_date' => date('Y-m-d H:i:s', strtotime($request->inputed_date . " " . $request->inputed_time)) ?? now(),
                'last_updated_date' => now(),
            ],
            'Pending' => [
                'status' => 'Pending',
                'last_progress_id' => $progress_id,
                'last_updated_date' => now(),
            ],
            'Closed' => [
                'closed_date' => $request->customer_confirm == 1 ? ($request->closed_date ?? now()) : null,
                'status' => $request->customer_confirm == 1 ? 'Closed' : 'Waiting Close',
                'last_progress_id' => $progress_id,
                'last_updated_date' => now(),
            ],
        ];

        $ticket->update($array[$type] ?? $array['All']);
    }

    // Get ticket after closed by service desk, when the ticket not in user verified
    public function getTicketWaitingClose()
    {
        $ticket = $this->model
            ->where(function ($query) {
                $query
                    ->whereNotNull('last_confirmation_date')
                    ->where('confirmation_count', '<', 3)
                    ->where('confirmation_count', '>', 0)
                    ->where('last_confirmation_date', '<=', now()->subHour());
            })
            ->orWhere('confirmation_count', 0)
            ->where('status', 'Waiting Close')
            ->with('ticketInfo')
            ->where('is_deleted', 0)
            ->get();
        return $ticket;
    }

    // Update status confirmation by customer
    public function updateStatusConfirmTicket($id)
    {
        $ticket = $this->model->find($id);
        $ticket->update([
            'confirmation_count' => $ticket->confirmation_count + 1,
            'last_confirmation_date' => now()
        ]);
        return $ticket;
    }

    // Update ticket to closed
    public function updateClosed($id)
    {
        $ticket = $this->model->find($id)->update(['status' => 'Closed', 'closed_date' => now()]);
        return $ticket;
    }

    // Get ticket with null rating
    public function getRatingNull()
    {
        $ticket = $this->model
            ->whereNull('rate')
            ->where('notif_rate', 0)
            ->where('confirmation_count', 3)
            ->with('ticketInfo')
            ->whereIn('status', ['Closed', 'Waiting Close'])
            ->where('is_deleted', 0)
            ->get();
        return $ticket;
    }

    // Update the notif rate of ticket
    public function updateStatusRate($id)
    {
        $ticket = $this->model->find($id)->update([
            'notif_rate' => true
        ]);

        return $ticket;
    }

    // Add rate submitted from email
    public function addRating($rate)
    {
        $ticket = $this->model->update([
            'rate'  => $rate,
        ]);

        return $ticket;
    }

    // Check is user have diagnose progress or no
    public function checkDiagnose($id)
    {
        $diagnose = $this->model->with(['troubleTicketProgress' => function ($query) {
            $query->where('update_type', 'Diagnose');
        }])
            ->find($id);
        return $diagnose;
    }

    // Edit the progress by ticket
    public function editProgress($request, $id)
    {
        return $this->troubleTicketProgress->find($id)->update($request);
    }

    // Edit ticket by column table
    public function editCreate($request, $id)
    {
        return $this->model->find($id)->update($request);
    }

    // Save record of push notification
    public function pushNotif($ticket, $notification)
    {
        return $ticket->notifications()->create($notification);
    }

    // Get last of departement included ticket
    public static function lastDepartment($id)
    {

        $ticket = self::$modelStatic
            ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id FROM trouble_ticket_progress WHERE update_type = "Dispatch" ORDER BY id DESC LIMIT 1) as ttp_dispatch'), function ($join) {
                $join->on('trouble_tickets.id', '=', 'ttp_dispatch.trouble_ticket_id')
                    ->leftJoin('dispatches as dsp', 'ttp_dispatch.id', '=', 'dsp.trouble_ticket_progress_id');
            })
            ->select('department_id', 'dsp.department_to_id')
            ->find($id);
        if (!empty($ticket->department_to_id)) :
            return $ticket->department_to_id;
        else :
            return $ticket->department_id;
        endif;
    }

    // Save record the ticket is readed
    public function readNotif($request, $id)
    {
        $notif = $this->notification->find($id)->update($request);

        return $notif;
    }

    // Get data for alert dashboard
    public function getAlert($request, $department)
    {
        // Get total of gangguan or gamas ticket
        if (in_array($request->type, ['gangguan', 'gamas'])) :
            $count = $this->model
                ->when($department != null, function ($query) use ($department) {
                    $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                        $query->whereIn('departments.id', $department)
                            ->where('trouble_ticket_progress.update_type', 'Dispatch')
                            ->orderBy('trouble_ticket_progress.id', 'desc')
                            ->limit(1);
                    })
                        ->orWhereHas('department', function ($query) use ($department) {
                            $query->whereIn('departments.id', $department);
                        });
                })
                ->when($request->type == 'gangguan' ? ['Non Gamas'] : ['Gamas Impact', 'Gamas Non Impact'], function ($query, $type) {
                    $query->whereIn('trouble_tickets.problem_type', $type);
                })
                ->where('is_deleted', 0)
                ->whereIn('trouble_tickets.status', ['Open', 'Pending']);
            return $count->count();
        else :
            return $this->model
                ->leftJoin(DB::raw('(SELECT id, trouble_ticket_id FROM trouble_ticket_progress WHERE update_type = "Diagnose") as ttp_diagnosa'), function ($join) {
                    $join->on('trouble_tickets.id', '=', 'ttp_diagnosa.trouble_ticket_id');
                })
                ->when($department != null, function ($query) use ($department) {
                    $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                        $query->whereIn('departments.id', $department)
                            ->where('trouble_ticket_progress.update_type', 'Dispatch')
                            ->orderBy('trouble_ticket_progress.id', 'desc')
                            ->limit(1);
                    })
                        ->orWhereHas('department', function ($query) use ($department) {
                            $query->whereIn('departments.id', $department);
                        });
                })
                ->whereIn('trouble_tickets.status', ['Open', 'Pending'])
                ->whereNull('ttp_diagnosa.id')
                ->where('is_deleted', 0)
                ->count();
        endif;
    }

    // Get total ticket by department
    public function getTotalTicket($department)
    {
        $ticket = $this->model
            ->join('departments', 'trouble_tickets.department_id', '=', 'departments.id')
            ->where('departments.id', $department)
            ->selectRaw('COUNT(CASE WHEN trouble_tickets.status = "Open" THEN 1 ELSE NULL END) as open_count')
            ->selectRaw('COUNT(CASE WHEN trouble_tickets.status = "Pending" THEN 1 ELSE NULL END) as pending_count')
            ->selectRaw('COUNT(CASE WHEN trouble_tickets.status = "Closed" THEN 1 ELSE NULL END) as closed_count')
            ->where('is_deleted', 0)
            ->get();

        return $ticket;
    }

    // Get MTTR of ticket
    public function getMTTR($department)
    {
        // Get total pending each ticket
        $subquery = DB::table('trouble_ticket_progress')
            ->select('trouble_ticket_id', DB::raw('SUM(TIMESTAMPDIFF(SECOND, created_at, closed_at)) as pending_time'))
            ->leftJoin('pendings', 'trouble_ticket_progress.id', '=', 'pendings.trouble_ticket_progress_id')
            ->where('pendings.pending_by', 'By Customer')
            ->groupBy('trouble_ticket_id');

        // Get pending info
        $subquery_nt = DB::table('trouble_tickets')
            ->select(DB::raw('trouble_tickets.nomor_ticket, trouble_tickets.id'))
            ->leftJoinSub($subquery, 'p', function ($join) {
                $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
            })
            ->orderByRaw('TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND) DESC');

        $mttr = $this->model
            ->select(DB::raw('MONTHNAME(trouble_tickets.created_date) as month'), DB::raw('COUNT(*), AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND)) as mttr, nt.nomor_ticket as max_ticket_number'))
            ->leftJoinSub($subquery, 'p', function ($join) {
                $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
            })
            ->leftJoinSub($subquery_nt, 'nt', function ($join) {
                $join->on('trouble_tickets.id', '=', 'nt.id');
            })
            // Filter specific user service desk
            ->whereIn('creator_id', [14, 15, 16])
            ->whereNotNull('technical_closed_date')
            ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'))
            ->when(!empty($department), function ($query) use ($department) {
                $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                    $query->where('departments.id', $department)
                        ->where('trouble_ticket_progress.update_type', 'Dispatch')
                        ->orderBy('trouble_ticket_progress.id', 'desc')
                        ->limit(1);
                })
                    ->orWhereHas('department', function ($query) use ($department) {
                        $query->where('departments.id', $department);
                    });
            })
            ->whereIn('trouble_tickets.type', ['Incident TTR'])
            ->where('trouble_tickets.status', 'Closed')
            ->where('is_deleted', 0)
            ->groupBy(DB::raw('MONTHNAME(trouble_tickets.created_date)'))
            ->orderByRaw('MONTH(trouble_tickets.created_date) ASC')
            ->get();


        // Group by month
        // Get month MTTR
        $month = date('F');
        $current_month_mttr = $this->model
            ->select(DB::raw('COUNT(*), AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND)) as mttr'))
            ->leftJoinSub($subquery, 'p', function ($join) {
                $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
            })
            ->whereIn('creator_id', [14, 15, 16])
            ->whereNotNull('technical_closed_date')
            ->when(!empty($department), function ($query) use ($department) {
                $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                    $query->where('departments.id', $department)
                        ->where('trouble_ticket_progress.update_type', 'Dispatch')
                        ->orderBy('trouble_ticket_progress.id', 'desc')
                        ->limit(1);
                })
                    ->orWhereHas('department', function ($query) use ($department) {
                        $query->where('departments.id', $department);
                    });
            })
            ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 1 MONTH)'))
            ->where(DB::raw('MONTHNAME(trouble_tickets.created_date)'), '=', $month)
            ->whereIn('trouble_tickets.type', ['Incident TTR'])
            ->where('trouble_tickets.status', 'Closed')
            ->where('is_deleted', 0)
            ->get();


        if ($department === 'IP Core Network') {
            $now = Carbon::now();
            $startDate = $now->startOfWeek();
            $weekNumber = $startDate->weekOfMonth;
            $year = $startDate->year;
            $endOfWeek = $now->copy()->endOfWeek();
            $endDate = $endOfWeek->format('Y-m-d');
            // Get ccurrent week MTTR
            $current_week_mttr = $this->model
                ->select(DB::raw('COUNT(*), WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) AS week_number, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND)) as mttr'))
                ->leftJoinSub($subquery, 'p', function ($join) {
                    $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
                })
                ->whereIn('creator_id', [14, 15, 16])
                ->whereNotNull('technical_closed_date')
                ->when(!empty($department), function ($query) use ($department) {
                    $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                        $query->where('departments.id', $department)
                            ->where('trouble_ticket_progress.update_type', 'Dispatch')
                            ->orderBy('trouble_ticket_progress.id', 'desc')
                            ->limit(1);
                    })
                        ->orWhereHas('department', function ($query) use ($department) {
                            $query->where('departments.id', $department);
                        });
                })
                ->whereBetween('trouble_tickets.created_date', [$startDate, $endDate])
                ->having('week_number', $weekNumber)
                ->whereYear('trouble_tickets.created_date', $year)
                ->whereIn('trouble_tickets.type', ['Incident TTR'])
                ->where('trouble_tickets.status', 'Closed')
                ->where('is_deleted', 0)
                ->groupBy(DB::raw('WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3)'))
                ->get() ?? null;
            // Get all weeek MTTR
            $week_mttr = $this->model
                ->select(DB::raw('COUNT(*), WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) AS week_number, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.created_date, technical_closed_date - INTERVAL COALESCE(p.pending_time, 0) SECOND)) as mttr, nt.nomor_ticket as max_ticket_number'))
                ->leftJoinSub($subquery, 'p', function ($join) {
                    $join->on('trouble_tickets.id', '=', 'p.trouble_ticket_id');
                })
                ->leftJoinSub($subquery_nt, 'nt', function ($join) {
                    $join->on('trouble_tickets.id', '=', 'nt.id');
                })
                ->whereIn('creator_id', [14, 15, 16])
                ->whereNotNull('technical_closed_date')
                ->when(!empty($department), function ($query) use ($department) {
                    $query->whereHas('ttpDispatch.departmentDispatch', function ($query) use ($department) {
                        $query->where('departments.id', $department)
                            ->where('trouble_ticket_progress.update_type', 'Dispatch')
                            ->orderBy('trouble_ticket_progress.id', 'desc')
                            ->limit(1);
                    })
                        ->orWhereHas('department', function ($query) use ($department) {
                            $query->where('departments.id', $department);
                        });
                })
                ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 1 WEEK)'))
                ->groupBy('week_number')
                ->orderByRaw('WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) ASC')
                ->whereIn('trouble_tickets.type', ['Incident TTR'])
                ->where('trouble_tickets.status', 'Closed')
                ->where('is_deleted', 0)
                ->get() ?? null;
        }

        $data = [
            'mttr_month' => [
                'current_mttr' => $current_month_mttr ?? 0,
                'mttr' => $mttr ?? null
            ],
        ];

        if (auth()->user()->role->role_name === 'Admin' || (in_array(auth()->user()->department_id, [2, 4]) && $department === 'IP Core Network')) {
            $data += ['mttr_week' => [
                'current_mttr' => $current_week_mttr ?? 0,
                'mttr' => $week_mttr ?? null
            ]];
        }


        return $data;
    }


    public function getMTTA()
    {

        $subquery = DB::table('trouble_tickets')
            ->select(DB::raw('trouble_tickets.nomor_ticket, trouble_tickets.id'))
            ->orderByRaw('TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date) DESC');

        $mtta = $this->model
            ->select(DB::raw('COUNT(*), MONTHNAME(trouble_tickets.created_date) as month, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date)) as mtta, nt.nomor_ticket as max_ticket_number'))
            ->leftJoinSub($subquery, 'nt', function ($join) {
                $join->on('trouble_tickets.id', '=', 'nt.id');
            })
            ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'))
            ->groupBy(DB::raw('MONTHNAME(trouble_tickets.created_date)'))
            ->orderByRaw('MONTH(trouble_tickets.created_date) ASC')
            ->whereNotNull('trouble_tickets.event_datetime')
            ->whereIn('creator_id', [14, 15, 16])
            ->where('is_deleted', 0)
            ->get();

        $month = date('F');

        $current_month_mtta = $this->model
            ->select(DB::raw('COUNT(*), MONTHNAME(trouble_tickets.created_date) as month, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date)) as mtta'))
            ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 1 MONTH)'))
            ->where(DB::raw('MONTHNAME(trouble_tickets.created_date)'), '=', $month)
            ->groupBy(DB::raw('MONTHNAME(trouble_tickets.created_date)'))
            ->whereNotNull('trouble_tickets.event_datetime')
            ->whereIn('creator_id', [14, 15, 16])
            ->where('is_deleted', 0)
            ->get() ?? null;

        $now = Carbon::now();
        $startDate = $now->startOfWeek();
        $weekNumber = $startDate->weekOfMonth;
        $year = $startDate->year;
        $endOfWeek = $now->copy()->endOfWeek();
        $endDate = $endOfWeek->format('Y-m-d');

        $current_week_mtta = $this->model
            ->select(DB::raw('COUNT(*), WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) AS week_number, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date)) as mtta'))
            ->whereBetween('trouble_tickets.created_date', [$startDate, $endDate])
            ->having('week_number', $weekNumber)
            ->whereYear('trouble_tickets.created_date', $year)
            ->whereIn('creator_id', [14, 15, 16])
            ->groupBy(DB::raw('WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3)'))
            ->orderByRaw('WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) ASC')
            ->whereNotNull('trouble_tickets.event_datetime')
            ->where('is_deleted', 0)
            ->get() ?? null;

        $week_mtta = $this->model
            ->select(DB::raw('COUNT(*), WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) AS week_number, AVG(TIMESTAMPDIFF(SECOND, trouble_tickets.event_datetime, trouble_tickets.created_date)) as mtta, nt.nomor_ticket as max_ticket_number'))
            ->leftJoinSub($subquery, 'nt', function ($join) {
                $join->on('trouble_tickets.id', '=', 'nt.id');
            })
            ->where('trouble_tickets.created_date', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 1 WEEK)'))
            ->groupBy('week_number')
            ->orderByRaw('WEEK(trouble_tickets.created_date, 3) - WEEK(DATE_SUB(trouble_tickets.created_date, INTERVAL DAY(trouble_tickets.created_date)-1 DAY), 3) ASC')
            ->whereNotNull('trouble_tickets.event_datetime')
            ->whereIn('creator_id', [14, 15, 16])
            ->where('is_deleted', 0)
            ->get() ?? null;


        $data = [
            'mtta_month' => [
                'current_mtta' => $current_month_mtta ?? 0,
                'mtta' => $mtta ?? null
            ],
        ];

        if (auth()->user()->role->role_name === 'Admin' || in_array(auth()->user()->department_id, [2, 4])) {
            $data += ['mtta_week' => [
                'current_mtta' => $current_week_mtta ?? 0,
                'mtta' => $week_mtta ?? null
            ]];
        }

        return $data;
    }

    public function getTopTicket()
    {
        $ticket = $this->model
            ->join('services', 'trouble_tickets.service_id', '=', 'services.id')
            ->selectRaw('COUNT(services.id) as service_top3, services.name')
            ->groupBy('services.id')
            ->orderBy('service_top3', 'desc')
            ->limit(3)
            ->where('is_deleted', 0)
            ->get();

        return $ticket;
    }

    public function getOpenMore3Days()
    {
        $ticket = $this->model->selectRaw('COUNT(CASE WHEN DATEDIFF(closed_date, created_date) > 3 THEN 1 ELSE NULL END) as open_more3days')->where('is_deleted', 0)->get();
        return $ticket;
    }

    public function getRate()
    {
        $rate = $this->model->selectRaw('ROUND(SUM(rate)/COUNT(*)) as mean_rate')
            ->whereNotNull('rate')
            ->where('is_deleted', 0)
            ->get();
        return $rate;
    }

    public function getTicketAfterOneHourCreated()
    {
        $ticket = $this->model
            ->with(['lastProgress', 'attachments', 'ticketInfo', 'eventLocation'])
            ->where('created_date', '<=', now()->subHour())
            ->where('notif_gm', 0)
            ->whereNotNull('notif_gm')
            ->where('is_deleted', 0)
            ->whereIn('status', ['Open', 'Pending'])
            ->get();


        return $ticket;
    }


    public function getMantoolsDatacom()
    {

        $allDatacom = $this->mantoolsDatacom->select('id', 'name', 'location', 'customer', 'datacom_perangkat_id', 'datacom_metro_e_id', 'datacom_vpn_ip_id', 'datacom_ip_transit_id', 'datacom_astinet_id')->get();

        return $allDatacom;
    }

    public function updateStatusNotifGM(int $id)
    {
        $ticket = $this->model
            ->find($id)
            ->update(['notif_gm' => true]);

        return $ticket;
    }

    public function deleteTicket($request, $id)
    {
        $ticket = $this->model->find($id);
        if (!$ticket) throw new Exception("Ticket tidak ditemukan!");

        $data_log = [
            'reason' => $request->reason,
            'deletable_id' => $id,
            'deleter_id' => auth()->id()
        ];

        $ticket->update(['is_deleted' => true]);
        $ticket->deleteLogs()->create($data_log);

        return;
    }
}
