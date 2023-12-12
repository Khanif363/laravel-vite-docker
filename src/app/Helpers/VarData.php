<?php

namespace App\Helpers;

class VarData
{
    public static function varSidebar()
    {
        $nav = [
            [
                'nama' => 'Dashboard',
                'link' => '/dashboard',
                'icon' => 'fas fa-house',
                'tooltip' => 'tooltip-dashboard',
            ],
            [
                'nama' => 'Ticket',
                'link' => '/tickets',
                'icon' => 'fas fa-ticket',
                'tooltip' => 'tooltip-tickets',
            ],
            [
                'nama' => 'Root Cause Analysis',
                'link' => '/problem-managements',
                'icon' => 'fas fa-magnifying-glass-chart',
                'tooltip' => 'tooltip-problem-managements',
            ],
            [
                'nama' => 'Changes',
                'link' => '/change-managements',
                'icon' => 'fas fa-recycle',
                'tooltip' => 'tooltip-change-managements',
            ],
            [
                'nama' => 'Katalog',
                'link' => '/catalogs',
                'icon' => 'fas fa-file-waveform',
                'tooltip' => 'tooltip-catalogs',
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
            [
                'nama' => 'Location',
                'link' => '/locations',
                'icon' => 'fas fa-location-dot',
                'tooltip' => 'tooltip-locations',
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
