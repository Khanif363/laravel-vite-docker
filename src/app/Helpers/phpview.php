<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('phpSidebar')) {
    function phpSidebar()
    {
        $nav = [
            [
                'nama' => 'Dashboard',
                'link' => '/',
                'icon' => 'fas fa-house',
                'tooltip' => 'tooltip-dashboard',
            ],
            [
                'nama' => 'Trouble Ticket',
                'link' => '/tickets',
                'icon' => 'fas fa-ticket',
                'tooltip' => 'tooltip-tickets',
            ],
            [
                'nama' => 'Problem Management',
                'link' => '/problem-managements',
                'icon' => 'fas fa-magnifying-glass-chart',
                'tooltip' => 'tooltip-problem-managements',
            ],
            [
                'nama' => 'Change Management',
                'link' => '/change-managements',
                'icon' => 'fas fa-recycle',
                'tooltip' => 'tooltip-change-managements',
            ],
            [
                'nama' => 'Department',
                'link' => '/departments',
                'icon' => 'fas fa-building',
                'tooltip' => 'tooltip-departments',
            ],
            [
                'nama' => 'Service',
                'link' => '/services',
                'icon' => 'fas fa-list',
                'tooltip' => 'tooltip-services',
            ],
            [
                'nama' => 'Resume Closing',
                'link' => '/resumes',
                'icon' => 'fas fa-file',
                'tooltip' => 'tooltip-resumes',
            ],
            [
                'nama' => 'Device',
                'link' => '/devices',
                'icon' => 'fas fa-laptop-code',
                'tooltip' => 'tooltip-devices',
            ],
            // [
            //     'nama' => 'Email Receiver',
            //     'link' => '/email_receivers',
            //     'icon' => 'fas fa-envelope',
            // ],
            [
                'nama' => 'User Management',
                'link' => '/user-managements',
                'icon' => 'fas fa-user',
                'tooltip' => 'tooltip-user-managements',
            ],
        ];

        return $nav;
    }
}
