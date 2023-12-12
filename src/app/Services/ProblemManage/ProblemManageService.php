<?php

namespace App\Services\ProblemManage;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\TroubleTicket\TroubleTicketService;
use App\Services\UserManagement\UserManagementService;
use App\Repositories\ProblemManage\ProblemManageRepository;
use App\Services\ProblemManage\ProblemManageServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProblemManageService implements ProblemManageServiceInterface
{
    private $mainRepository;
    private $ticketService;
    private $userService;

    public function __construct(ProblemManageRepository $mainRepository, TroubleTicketService $ticketService, UserManagementService $userService)
    {
        $this->mainRepository = $mainRepository;
        $this->ticketService = $ticketService;
        $this->userService = $userService;
    }

    public function getRolePermission($part_permission = '')
    {
        $rolepermission = $this->userService->rolePermission($part_permission);
        return $rolepermission;
    }

    public function getProblemManageAll(object $request)
    {


        $department = null;
        $part_permission = ['name' => 'View List Root Cause Analysis'];
        $rolepermission = $this->getRolePermission($part_permission);
        $permission_depart = $rolepermission->accessRights->pluck('department_id')->toArray();
        if (($rolepermission->role->role_name ?? null) !== 'Admin' && auth()->user()->department_id !== 4) :
            $department = $permission_depart;
        endif;

        $data = $this->mainRepository->getProblemManageAll($request, $department);
        return $data;
    }

    public function findById(int $id): object
    {
        $data = $this->mainRepository->findById($id);
        return $data;
    }
    public function detailProblem(int $id)
    {
        $data = $this->mainRepository->detailProblem($id);
        if (empty($data)) {
            throw new BadRequestException("Data tidak ditemukan!");
        }
        return $data;
    }

    public function exportProblemManage(object $request): object
    {
        $data = $this->mainRepository->exportProblemManage($request);
        return $data;
    }

    public function createProblem(object $request)
    {
        $ticket = $this->ticketService->findByNomor($request->nomor_ticket ?? '');
        // Log::info($ticket);
        $now = Carbon::now();
        $year = $now->format('y');
        $month = $now->month;
        $check = $this->mainRepository->model()->count();
        if ($check == 0) {
            $order = 100001;
            $nomor_problem = 'RCA' . $year . $month . $order;
        } else {
            $last = $this->mainRepository->lastData();
            $order = (int)substr($last->nomor_problem, -6) + 1;
            $nomor_problem = 'RCA' . $year . $month . $order;
        }
        $status_verif = in_array(auth()->user()->role_id, [2, 3]) && in_array(auth()->user()->department_id, [$ticket->ttpDispatch->departmentDispatch->name ?? $ticket->department->name ?? null]) ? 1 : 0;
        $verif = ['is_verified' => $status_verif];
        $data = $this->mainRepository->createProblem($request, $nomor_problem, $verif);
        return $data;
    }

    public function updateProblem(object $request, int $id)
    {
        $problem = $this->mainRepository->findById($id);

        if ($problem->is_verified === 0)
            throw new BadRequestException("Tunggu persetujuan ticket!");

        if ($problem->is_verified === 2)
            throw new BadRequestException("Persetujuan ditolak!");

        if ($problem->troubleTicket->status !== 'Closed')
            throw new BadRequestException("Ticket belum bisa diproses sebagai RCA!");

        $data = $this->mainRepository->updateProblem($request, $id);
        return $data;
    }

    public function verifProblem($request, $id)
    {
        $problem = $this->mainRepository->findById($id);
        if ($problem->is_verified != null) :
            throw new BadRequestException("Sudah diverifikasi, Tidak perlu diverifikasi ulang!");
        endif;

        $data = $this->mainRepository->verifProblem($request, $id);
        return $data;
    }
}
