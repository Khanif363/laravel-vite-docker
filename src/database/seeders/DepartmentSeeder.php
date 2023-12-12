<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            [
                'name'          => 'IT & Cyber Security',
                'status'        => 1,
                'is_desk'       => 0,
                'is_core'       => 1
            ],
            [
                'name'          => 'IP Core Network',
                'status'        => 1,
                'is_desk'       => 0,
                'is_core'       => 1
            ],
            [
                'name'          => 'IT Integration',
                'status'        => 1,
                'is_desk'       => 0,
                'is_core'       => 1
            ],
            [
                'name'          => 'Service Desk',
                'status'        => 1,
                'is_desk'       => 1,
                'is_core'       => 0
            ],
        ];

        Department::insert($array);
    }
}
