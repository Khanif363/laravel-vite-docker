<?php

use App\Models\Department;
use App\Models\User;
use App\Models\Service;
use App\Models\Engineer;
use App\Models\Resume;
use App\Repositories\TroubleTicket\TroubleTicketRepository;

if (!function_exists('createEngineer')) {
    function createEngineer($engineer_assignment_id, $user_id)
    {
        $engineer = Engineer::create([
            'engineer_assignment_id'        => $engineer_assignment_id,
            'user_id'                       => $user_id,
        ]);

        return $engineer;
    }
}


if (!function_exists('serviceList')) {
    function serviceList()
    {
        $service = Service::select('id', 'name')->get();

        return $service;
    }
}

if (!function_exists('userList')) {
    function userList()
    {
        $user = User::select('id', 'full_name')->get();
        return $user;
    }
}


if (!function_exists('departmentNotCore')) {
    function departmentNotCore()
    {
        $department = Department::select('id', 'name')
            ->where('is_core', 0)
            ->get();
        return $department;
    }
}

if (!function_exists('departmentSemiCore')) {
    function departmentSemiCore()
    {
        $department = Department::select('id', 'name')
            ->where('is_core', 1)
            ->orWhere('is_desk', 1)
            ->get();
        return $department;
    }
}

if (!function_exists('departmentForDispatch')) {
    function departmentForDispatch($id)
    {
        $lastDepartment = TroubleTicketRepository::lastDepartment($id);
        $department = Department::select('id', 'name')
            ->whereNot('id', $lastDepartment->id)
            ->where('is_core', 1)
            ->orWhere('is_desk', 1)
            ->get();
        return $department;
    }
}
if (!function_exists('resumeList')) {
    function resumeList()
    {
        $resume = Resume::select('id', 'name')
            ->where('status', 1)
            ->get();
        return $resume;
    }
}
