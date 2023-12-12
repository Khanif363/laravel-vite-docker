<?php

namespace Database\Seeders;

use App\Models\Resume;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Restart Perangkat',
                'department_id' => 1,
                'status' => 1,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Ganti Perangkat',
                'department_id' => 1,
                'status' => 1,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Reconfig',
                'department_id' => 1,
                'status' => 1,
            ),
            3 =>
            array(
                'id' => 7,
                'name' => 'Bug Fixing',
                'department_id' => 2,
                'status' => 1,
            ),
            4 =>
            array(
                'id' => 8,
                'name' => 'Restart Service Web Server',
                'department_id' => 2,
                'status' => 1,
            ),
            5 =>
            array(
                'id' => 9,
                'name' => 'Restart Service Database',
                'department_id' => 2,
                'status' => 1,
            ),
            6 =>
            array(
                'id' => 10,
                'name' => 'Reset Password Default Email',
                'department_id' => 4,
                'status' => 1,
            ),
            7 =>
            array(
                'id' => 11,
                'name' => 'Implementasi',
                'department_id' => 3,
                'status' => 1,
            ),
            8 =>
            array(
                'id' => 12,
                'name' => 'Request Whitelist',
                'department_id' => 3,
                'status' => 1,
            ),
            9 =>
            array(
                'id' => 13,
                'name' => 'User Awareness',
                'department_id' => 3,
                'status' => 1,
            ),
            10 =>
            array(
                'id' => 14,
                'name' => 'No Authentication',
                'department_id' => 3,
                'status' => 1,
            ),
            11 =>
            array(
                'id' => 15,
                'name' => 'Block IP',
                'department_id' => 3,
                'status' => 1,
            ),
            12 =>
            array(
                'id' => 16,
                'name' => 'Implementasi Access Login Wifi (LDAP/Radius)',
                'department_id' => 3,
                'status' => 1,
            ),
            13 =>
            array(
                'id' => 17,
                'name' => 'Milis Telah dibuat',
                'department_id' => 4,
                'status' => 1,
            ),
            14 =>
            array(
                'id' => 18,
                'name' => 'Setting Perangkat',
                'department_id' => 4,
                'status' => 1,
            ),
            15 =>
            array(
                'id' => 19,
                'name' => 'Reset',
                'department_id' => 4,
                'status' => 1,
            ),
            16 =>
            array(
                'id' => 20,
                'name' => 'Active/Closed/Locked',
                'department_id' => 4,
                'status' => 1,
            ),
            17 =>
            array(
                'id' => 21,
                'name' => 'Reset User Ldap',
                'department_id' => 1,
                'status' => 1,
            ),
            18 =>
            array(
                'id' => 22,
                'name' => 'Reset Password Ldap',
                'department_id' => 4,
                'status' => 1,
            ),
            19 =>
            array(
                'id' => 23,
                'name' => 'ReLogin',
                'department_id' => 4,
                'status' => 1,
            ),
            20 =>
            array(
                'id' => 24,
                'name' => 'Perubahan Oleh Admin',
                'department_id' => 4,
                'status' => 1,
            ),
            21 =>
            array(
                'id' => 25,
                'name' => 'Instal Aplikasi',
                'department_id' => 4,
                'status' => 1,
            ),
            22 =>
            array(
                'id' => 26,
                'name' => 'Installasi',
                'department_id' => 4,
                'status' => 1,
            ),
            23 =>
            array(
                'id' => 27,
                'name' => 'Creat Akun ',
                'department_id' => 4,
                'status' => 1,
            ),
            24 =>
            array(
                'id' => 28,
                'name' => 'Input',
                'department_id' => 4,
                'status' => 1,
            ),
            25 =>
            array(
                'id' => 29,
                'name' => 'Topup Kuota SIAPBRO',
                'department_id' => 2,
                'status' => 1,
            ),
            26 =>
            array(
                'id' => 30,
                'name' => 'Dilanjutkan oleh Tim IT Service Management',
                'department_id' => 6,
                'status' => 1,
            ),
            27 =>
            array(
                'id' => 31,
                'name' => 'Dilanjutkan oleh Tim IT Core Network & Digital Infra',
                'department_id' => 6,
                'status' => 1,
            ),
            28 =>
            array(
                'id' => 32,
                'name' => 'Dilanjutkan oleh Tim Digital Platform Solution',
                'department_id' => 6,
                'status' => 1,
            ),
            29 =>
            array(
                'id' => 33,
                'name' => 'Dilanjutkkan oleh Tim IT & Digital Cyber Security',
                'department_id' => 6,
                'status' => 1,
            ),
            30 =>
            array(
                'id' => 34,
                'name' => 'Dilanjutkan oleh Tim IT & Digital Service',
                'department_id' => 6,
                'status' => 1,
            ),
            31 =>
            array(
                'id' => 35,
                'name' => 'Scan Antivirus / Antimalware',
                'department_id' => 3,
                'status' => 1,
            ),
            32 =>
            array(
                'id' => 36,
                'name' => 'Edit Via Database',
                'department_id' => 7,
                'status' => 1,
            ),
            33 =>
            array(
                'id' => 37,
                'name' => 'Reset Password Email',
                'department_id' => 4,
                'status' => 1,
            ),
            34 =>
            array(
                'id' => 38,
                'name' => 'Edit Coding',
                'department_id' => 7,
                'status' => 1,
            ),
            35 =>
            array(
                'id' => 39,
                'name' => 'Konfirmasi',
                'department_id' => 7,
                'status' => 1,
            ),
            36 =>
            array(
                'id' => 40,
                'name' => 'Service',
                'department_id' => 4,
                'status' => 1,
            ),
            37 =>
            array(
                'id' => 41,
                'name' => 'Penambahan Rule Apliklasi',
                'department_id' => 4,
                'status' => 1,
            ),
            38 =>
            array(
                'id' => 42,
                'name' => 'Update Milis',
                'department_id' => 4,
                'status' => 1,
            ),
            39 =>
            array(
                'id' => 43,
                'name' => 'Instalasi Berhasil',
                'department_id' => 1,
                'status' => 1,
            ),
            40 =>
            array(
                'id' => 44,
                'name' => 'Tambah Users di Unit',
                'department_id' => 4,
                'status' => 1,
            ),
            41 =>
            array(
                'id' => 45,
                'name' => 'False Positive',
                'department_id' => 3,
                'status' => 1,
            ),
            42 =>
            array(
                'id' => 46,
                'name' => 'Topup Kuota SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            43 =>
            array(
                'id' => 47,
                'name' => 'Restart Perangkat',
                'department_id' => 4,
                'status' => 1,
            ),
            44 =>
            array(
                'id' => 48,
                'name' => 'Penambahan Data di SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            45 =>
            array(
                'id' => 49,
                'name' => 'Vicon Sudah Selesai',
                'department_id' => 4,
                'status' => 1,
            ),
            46 =>
            array(
                'id' => 50,
                'name' => 'Create User SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            47 =>
            array(
                'id' => 51,
                'name' => 'Disable WLAN',
                'department_id' => 4,
                'status' => 1,
            ),
            48 =>
            array(
                'id' => 52,
                'name' => 'Enable WLAN',
                'department_id' => 1,
                'status' => 1,
            ),
            49 =>
            array(
                'id' => 53,
                'name' => 'Pembayaran Indihome',
                'department_id' => 4,
                'status' => 1,
            ),
            50 =>
            array(
                'id' => 54,
                'name' => 'Pembukaan Rule Blokir',
                'department_id' => 7,
                'status' => 1,
            ),
            51 =>
            array(
                'id' => 55,
                'name' => 'Create User ICHA',
                'department_id' => 7,
                'status' => 1,
            ),
            52 =>
            array(
                'id' => 56,
                'name' => 'Non Aktiv Account',
                'department_id' => 4,
                'status' => 1,
            ),
            53 =>
            array(
                'id' => 57,
                'name' => 'Export Log Topup SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            54 =>
            array(
                'id' => 58,
                'name' => 'Laptop/PC',
                'department_id' => 4,
                'status' => 1,
            ),
            55 =>
            array(
                'id' => 59,
                'name' => 'Global Whitelist',
                'department_id' => 3,
                'status' => 1,
            ),
            56 =>
            array(
                'id' => 60,
                'name' => 'Upload Data SIAPBRO',
                'department_id' => 2,
                'status' => 1,
            ),
            57 =>
            array(
                'id' => 61,
                'name' => 'Upload Data SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            58 =>
            array(
                'id' => 62,
                'name' => 'Upload Data Excel',
                'department_id' => 7,
                'status' => 1,
            ),
            59 =>
            array(
                'id' => 63,
                'name' => 'Instal Ulang windows',
                'department_id' => 4,
                'status' => 1,
            ),
            60 =>
            array(
                'id' => 64,
                'name' => 'Update Data SIAPBRO',
                'department_id' => 2,
                'status' => 1,
            ),
            61 =>
            array(
                'id' => 65,
                'name' => 'Create Akun SIAPBRO',
                'department_id' => 2,
                'status' => 1,
            ),
            62 =>
            array(
                'id' => 66,
                'name' => 'Configurasi Jalur Link Internet',
                'department_id' => 4,
                'status' => 1,
            ),
            63 =>
            array(
                'id' => 67,
                'name' => 'Update Profile VPN',
                'department_id' => 4,
                'status' => 1,
            ),
            64 =>
            array(
                'id' => 68,
                'name' => 'Creat Room Vicon ',
                'department_id' => 4,
                'status' => 1,
            ),
            65 =>
            array(
                'id' => 69,
                'name' => 'Pemindahan Host Vicon',
                'department_id' => 4,
                'status' => 1,
            ),
            66 =>
            array(
                'id' => 70,
                'name' => 'Pemindahan Source Internet',
                'department_id' => 4,
                'status' => 1,
            ),
            67 =>
            array(
                'id' => 71,
                'name' => 'Update Data Remote Nagios',
                'department_id' => 4,
                'status' => 1,
            ),
            68 =>
            array(
                'id' => 72,
                'name' => 'Kurang Perangkat',
                'department_id' => 4,
                'status' => 1,
            ),
            69 =>
            array(
                'id' => 73,
                'name' => 'Restart Service',
                'department_id' => 4,
                'status' => 1,
            ),
            70 =>
            array(
                'id' => 74,
                'name' => 'Plug Unplug',
                'department_id' => 1,
                'status' => 1,
            ),
            71 =>
            array(
                'id' => 75,
                'name' => 'Export Data SIAPBRO',
                'department_id' => 2,
                'status' => 1,
            ),
            72 =>
            array(
                'id' => 76,
                'name' => 'Develop aplikasi baru',
                'department_id' => 7,
                'status' => 1,
            ),
            73 =>
            array(
                'id' => 77,
                'name' => 'Penambahan Hak Akses Modul 3Easy',
                'department_id' => 4,
                'status' => 1,
            ),
            74 =>
            array(
                'id' => 78,
                'name' => 'Creat Monitoring Nagios',
                'department_id' => 4,
                'status' => 1,
            ),
            75 =>
            array(
                'id' => 79,
                'name' => 'Upgrade',
                'department_id' => 4,
                'status' => 1,
            ),
            76 =>
            array(
                'id' => 80,
                'name' => 'Kuota Mangoesky',
                'department_id' => 4,
                'status' => 1,
            ),
            77 =>
            array(
                'id' => 81,
                'name' => 'Upload Data',
                'department_id' => 2,
                'status' => 1,
            ),
            78 =>
            array(
                'id' => 82,
                'name' => 'Edit Coding',
                'department_id' => 4,
                'status' => 1,
            ),
            79 =>
            array(
                'id' => 83,
                'name' => 'Access Door',
                'department_id' => 4,
                'status' => 1,
            ),
            80 =>
            array(
                'id' => 84,
                'name' => 'Pengganti Unit Laptop',
                'department_id' => 4,
                'status' => 1,
            ),
            81 =>
            array(
                'id' => 85,
                'name' => 'Pengiriman Barang',
                'department_id' => 4,
                'status' => 1,
            ),
            82 =>
            array(
                'id' => 86,
                'name' => 'Cloud Telkomast',
                'department_id' => 4,
                'status' => 1,
            ),
            83 =>
            array(
                'id' => 87,
                'name' => 'Pembuatan Akun Mantools',
                'department_id' => 2,
                'status' => 1,
            ),
            84 =>
            array(
                'id' => 88,
                'name' => 'Troubleshoot',
                'department_id' => 3,
                'status' => 1,
            ),
            85 =>
            array(
                'id' => 89,
                'name' => 'Blocked By Rule',
                'department_id' => 3,
                'status' => 1,
            ),
            86 =>
            array(
                'id' => 90,
                'name' => 'Vicon Dibatalkan',
                'department_id' => 4,
                'status' => 1,
            ),
            87 =>
            array(
                'id' => 91,
                'name' => 'Pemindahan Perangkat',
                'department_id' => 4,
                'status' => 1,
            ),
            88 =>
            array(
                'id' => 92,
                'name' => 'Test Genose Selesai',
                'department_id' => 4,
                'status' => 1,
            ),
            89 =>
            array(
                'id' => 93,
                'name' => 'Development Aplikasi',
                'department_id' => 2,
                'status' => 1,
            ),
            90 =>
            array(
                'id' => 94,
                'name' => 'Integrasi Aplikasi',
                'department_id' => 2,
                'status' => 1,
            ),
            91 =>
            array(
                'id' => 95,
                'name' => 'Dokumen Selesai Sirkulir',
                'department_id' => 1,
                'status' => 1,
            ),
            92 =>
            array(
                'id' => 96,
                'name' => 'UAT (User Acceptance Test)',
                'department_id' => 2,
                'status' => 1,
            ),
            93 =>
            array(
                'id' => 97,
                'name' => 'UAT (User Acceptance Test)',
                'department_id' => 6,
                'status' => 0,
            ),
            94 =>
            array(
                'id' => 98,
                'name' => 'UAT (User Acceptance Test)',
                'department_id' => 7,
                'status' => 1,
            ),
            95 =>
            array(
                'id' => 99,
                'name' => 'Reaktivasi layanan di SIAPBRO',
                'department_id' => 7,
                'status' => 1,
            ),
            96 =>
            array(
                'id' => 100,
                'name' => 'Pembuatan User',
                'department_id' => 7,
                'status' => 1,
            ),
            97 =>
            array(
                'id' => 101,
                'name' => 'Blast Informasi',
                'department_id' => 4,
                'status' => 1,
            ),
            98 =>
            array(
                'id' => 102,
                'name' => 'Others',
                'department_id' => null,
                'status' => 1,
            ),
        );

        Resume::insert($array);
    }
}
