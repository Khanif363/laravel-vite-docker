<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Resume;
use App\Models\Service;
use App\Models\Department;
use App\Models\Device;
use App\Repositories\TroubleTicket\TroubleTicketRepository;

class SelectView
{
    public static function update_type()
    {
        $types = ['Diagnose', 'Dispatch', 'Pending', 'Engineer Assignment', 'Engineer Troubleshoot', 'Technical Close', 'Monitoring', 'Closed'];
        return $types;
    }

    public static function pending()
    {
        $pending = ['By Customer', 'By Engineer'];
        return $pending;
    }

    public static function serviceAll()
    {
        $services = Service::select('id', 'name')->get();
        return $services;
    }

    public static function userAll()
    {
        $users = User::select('id', 'full_name')->get();
        return $users;
    }

    public static function engineerAll()
    {
        $engineers = User::select('id', 'full_name')
            ->where('role_id', 4)
            ->get();

        return $engineers;
    }

    public static function departmentCore()
    {
        $departments = Department::select('id', 'name')
            ->where('status', 1)
            ->where('is_core', 1)
            ->get();

        return $departments;
    }

    public static function departmentAll()
    {
        $departments = Department::select('id', 'name')
            ->where('status', 1)
            ->get();
        return $departments;
    }

    public static function departmentNotCore()
    {
        $departments = Department::select('id', 'name')
            ->where('is_core', 0)
            ->where('status', 1)
            ->where('is_desk', 0)
            ->get();
        return $departments;
    }

    public static function departmentSemiCore()
    {
        $departments = Department::select('id', 'name')
            ->where('status', 1)
            ->where('is_core', 1)
            ->orWhere('is_desk', 1)
            ->get();
        return $departments;
    }

    public static function departmentForDispatch($id)
    {
        $lastDepartment = TroubleTicketRepository::lastDepartment($id);
        $department = Department::select('id', 'name')
            ->whereNot('id', $lastDepartment->id)
            ->where('status', 1)
            ->where('is_core', 1)
            ->orWhere('is_desk', 1)
            ->get();
        return $department;
    }

    public static function resumeAll()
    {
        $resums = Resume::select('id', 'name')
            ->where('status', 1)
            ->get();
        return $resums;
    }

    public static function deviceAll()
    {
        $devices = Device::select('id', 'name')->get();
        return $devices;
    }
}
