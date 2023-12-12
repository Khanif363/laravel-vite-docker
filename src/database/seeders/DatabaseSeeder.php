<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\DeviceSeeder;
use Database\Seeders\ResumeSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\AccessRightSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            AccessRightSeeder::class,
            DepartmentSeeder::class,
            DeviceSeeder::class,
            ResumeSeeder::class,
            RoleSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            LocationSeeder::class
        ]);
    }
}
