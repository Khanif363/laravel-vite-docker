<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'role_name'     => 'Admin',
                'is_active'     => 1
            ],
            [
                'role_name'     => 'General Manager',
                'is_active'     => 1
            ],
            [
                'role_name'     => 'Manager',
                'is_active'     => 1
            ],
            [
                'role_name'     => 'Engineer',
                'is_active'     => 1
            ],
        ];

        // Role::truncate();
        Role::insert($array);
    }
}
