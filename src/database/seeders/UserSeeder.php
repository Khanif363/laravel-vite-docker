<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array (
            0 =>
            array (
                'full_name' => 'Abror Khanif',
                'username' => '00220256',
                'password' => Hash::make('PsComit2027Admin'),
                'email' => 'abrorkhanif3299@gmail.com',
                'nik' => '00220256',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 1
            ),
            1 =>
            array (
                'full_name' => 'Asrafin Danang P',
                'username' => '916595',
                'password' => Hash::make('PsComit2027'),
                'email' => 'asrafin.danang@telkomsat.co.id',
                'nik' => '915263',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            2 =>
            array (
                'full_name' => 'Wendi Maksalmina',
                'username' => '916031',
                'password' => Hash::make('PsComit2027'),
                'email' => 'wendy.maxalmina@telkomsat.co.id',
                'nik' => '916031',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            3 =>
            array (
                'full_name' => 'Ari Nuzul Fajri',
                'username' => '765717',
                'password' => Hash::make('PsComit2027'),
                'email' => 'ari.nuzul@telkomsat.co.id',
                'nik' => '765717',
                'department_id' => 1,
                'is_enable'     => 1,
                'role_id'       => 3
            ),
            4 =>
            array (
                'full_name' => 'Imam Santoso',
                'username' => '785840',
                'password' => Hash::make('PsComit2027'),
                'email' => 'imamsant@telkomsat.co.id',
                'nik' => '785840',
                'department_id' => 2,
                'is_enable'     => 1,
                'role_id'       => 3
            ),
            5 =>
            array (
                'full_name' => 'Ajianto Wibowo',
                'username' => '835066',
                'password' => Hash::make('PsComit2027'),
                'email' => 'aawibowo@telkomsat.co.id',
                'nik' => '835066',
                'department_id' => 3,
                'is_enable'     => 1,
                'role_id'       => 3
            ),
            6 =>
            array (
                'full_name' => 'Arie Setyo',
                'username' => '875183',
                'password' => Hash::make('PsComit2027'),
                'email' => 'asetyoutomo@telkomsat.co.id',
                'nik' => '875183',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            7 =>
            array (
                'full_name' => 'Firman Putra Utama',
                'username' => '9519003',
                'password' => Hash::make('PsComit2027'),
                'email' => 'firman.putra@telkomsat.co.id',
                'nik' => '9519003',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            8 =>
            array (
                'full_name' => 'Buchori Ahmad',
                'username' => '8714005',
                'password' => Hash::make('PsComit2027'),
                'email' => 'buchori.ahmad@telkomsat.co.id',
                'nik' => '8714005',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 9 =>
            // array (
            //     'full_name' => 'Chandra Jentak',
            //     'username' => '2011137',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '2011137',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 10 =>
            // array (
            //     'full_name' => 'Bahari',
            //     'username' => 'bahari_SOC',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'bahari_SOC',
            //     'department_id' => 2,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 11 =>
            // array (
            //     'full_name' => 'Abdul Latif',
            //     'username' => '9117003',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '9117003',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            12 =>
            array (
                'full_name' => 'Mohamad Lukman',
                'username' => '89210009',
                'password' => Hash::make('PsComit2027'),
                'email' => 'mohamadlukmanhk@icloud.com',
                'nik' => '8920001',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 13 =>
            // array (
            //     'full_name' => 'Septiyono',
            //     'username' => '9018004',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '9018004',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 14 =>
            // array (
            //     'full_name' => 'Anggi Mondera',
            //     'username' => '905694',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '905694',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 15 =>
            // array (
            //     'full_name' => 'Tubagus Fajar Setiaw',
            //     'username' => '916594',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '916594',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 19 =>
            // array (
            //     'full_name' => 'Teguh Oki',
            //     'username' => '896086',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '896086',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 20 =>
            // array (
            //     'full_name' => 'Faris P',
            //     'username' => '9214001',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '9214001',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 23 =>
            // array (
            //     'full_name' => 'Mursanto',
            //     'username' => '8114001',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '8114001',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            24 =>
            array (
                'full_name' => 'Angela Primasari',
                'username' => '9418001',
                'password' => Hash::make('PsComit2027'),
                'email' => 'angela.primasari@telkomsat.co.id',
                'nik' => '9418001',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 28 =>
            // array (
            //     'full_name' => 'Rinindya Nurtiara Puteri',
            //     'username' => '94210006',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '94210006',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 31 =>
            // array (
            //     'full_name' => 'Egis Rafdani',
            //     'username' => '9219044',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '9219044',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 32 =>
            array (
                'full_name' => 'Ubaidilah',
                'username' => '97210011',
                'password' => Hash::make('PsComit2027'),
                'email' => 'ubaidilah1705@gmail.com',
                'nik' => '97210011',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            33 =>
            array (
                'full_name' => 'Erlangga Adinegoro',
                'username' => 'erlangga.adinegoro',
                'password' => Hash::make('PsComit2027'),
                'email' => 'erlangga.adinegoro@telkomsat.co.id',
                'nik' => 'erlangga.adinegoro',
                'department_id' => null,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 35 =>
            // array (
            //     'full_name' => 'Nindy Hedya',
            //     'username' => '9215004',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '9215004',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 37 =>
            // array (
            //     'full_name' => 'Mursanto',
            //     'username' => '816011',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '816011',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 38 =>
            // array (
            //     'full_name' => 'Putri Awalia',
            //     'username' => 'putri.awalia',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'putri.awal',
            //     'department_id' => 4,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            39 =>
            array (
                'full_name' => 'Tedy Anggara',
                'username' => '9317002',
                'password' => Hash::make('PsComit2027'),
                'email' => 'tedy.anggara@telkomsat.co.id',
                'nik' => '9317002',
                'department_id' => 4,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 40 =>
            // array (
            //     'full_name' => 'Faris Jawad',
            //     'username' => 'faris.jawad',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'faris.jawa',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 41 =>
            // array (
            //     'id' => 42,
            //     'full_name' => 'Hilmi Dafa',
            //     'username' => 'hilmi.dafa',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'hilmi.dafa',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 42 =>
            // array (
            //     'full_name' => 'Ryan',
            //     'username' => 'ryan',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'ryan',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 43 =>
            // array (
            //     'id' => 44,
            //     'full_name' => 'diaz',
            //     'username' => 'diaz',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'diaz',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 44 =>
            // array (
            //     'full_name' => 'Melinda Utami',
            //     'username' => '99220259',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '99220259',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 45 =>
            // array (
            //     'full_name' => 'Abror Khanif',
            //     'username' => 'abror.khanif',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'abror.khan',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 46 =>
            // array (
            //     'full_name' => 'Anggi Wiyani Putri Nasution',
            //     'username' => '96220266',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '96220266',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 47 =>
            array (
                'full_name' => 'Syafa Athaya',
                'username' => 'syafa.athaya',
                'password' => Hash::make('PsComit2027'),
                'email' => 'syafacode.01@gmail.com',
                'nik' => 'syafa.atha',
                'department_id' => 4,
                'is_enable'     => 1,
                'role_id'       => 4
            ),
            // 48 =>
            // array (
            //     'full_name' => 'Suci Ramdani',
            //     'username' => 'suci.ramdani',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'suci.ramda',
            //     'department_id' => 4,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 49 =>
            // array (
            //     'full_name' => 'Dhimita',
            //     'username' => 'dhimita',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => 'dhimita',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
            // 50 =>
            // array (
            //     'full_name' => 'M Andyk',
            //     'username' => '96210198',
            //     'password' => Hash::make('PsComit2027'),
            //     'email' => '',
            //     'nik' => '96210198',
            //     'department_id' => null,
            //     'is_enable'     => 1,
            //     'role_id'       => 4
            // ),
        );

        User::insert($users);
    }
}
