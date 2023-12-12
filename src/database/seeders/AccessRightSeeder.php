<?php

namespace Database\Seeders;

use App\Models\AccessRight;
use Illuminate\Database\Seeder;

class AccessRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            // [
            //     'access_name' => 'View Dashboard',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'View List Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'View List Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'View List Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'View Detail Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'View Detail Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'View Detail Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'View Detail Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Send Resume Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Send Resume Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Send Resume Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Send Resume Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Create Ticket',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Update Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Update Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Update Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Close Ticket',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Root Cause Analysis',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'View List Root Cause Analysis',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'View List Root Cause Analysis',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'View List Root Cause Analysis',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Create Root Cause Analysis',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Create Root Cause Analysis',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Create Root Cause Analysis',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Create Root Cause Analysis',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Update Root Cause Analysis',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Update Root Cause Analysis',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Update Root Cause Analysis',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Update Root Cause Analysis',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Verif Root Cause Analysis',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Verif Root Cause Analysis',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Verif Root Cause Analysis',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Verif Root Cause Analysis',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'View List Changes',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Changes',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Verif Changes',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Katalog',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Katalog',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Department',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Department',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update Department',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Service',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Service',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update Service',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Resume',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Resume',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update Resume',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List Device',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create Device',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update Device',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'View List User Management',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Create User Management',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Update User Management',
            //     'department_id' => null
            // ],


            // Seeder Fase2
            // [
            //     'access_name' => 'View Detail Changes',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Push Notification Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Push Notification Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Push Notification Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Push Notification Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'Edit Progress Ticket',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'Edit Progress Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Edit Progress Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Edit Progress Ticket',
            //     'department_id' => 4
            // ],
            // [
            //     'access_name' => 'View Detail Root Cause Analysis',
            //     'department_id' => 1
            // ],
            // [
            //     'access_name' => 'View Detail Root Cause Analysis',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'View Detail Root Cause Analysis',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'View Detail Root Cause Analysis',
            //     'department_id' => 4
            // ],


            // [
            //     'access_name' => 'Close Ticket',
            //     'department_id' => 2
            // ],
            // [
            //     'access_name' => 'Close Ticket',
            //     'department_id' => 3
            // ],
            // [
            //     'access_name' => 'Close Ticket',
            //     'department_id' => 4
            // ],


            // [
            //     'access_name' => 'Delete Ticket',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Delete Changes',
            //     'department_id' => null
            // ],
            // [
            //     'access_name' => 'Edit Changes',
            //     'department_id' => null
            // ],
            [
                'access_name' => 'View All Ticket',
                'department_id' => null
            ]
        ];
        // AccessRight::truncate();
        AccessRight::insert($array);
    }
}
