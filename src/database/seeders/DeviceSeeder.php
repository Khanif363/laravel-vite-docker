<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
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
                'name' => 'Router Dummy',
                'brand' => 'Cisco',
                'type' => '1181',
                'hostname' => 'Router-A',
                'sn' => '987654321',
                'status' => 1,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'AP Dummy',
                'brand' => 'TP-Link',
                'type' => 'WL772',
                'hostname' => 'APDummy-A',
                'sn' => '123456789',
                'status' => 1,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'PC Controler',
                'brand' => 'Rakitan',
                'type' => 'Custom',
                'hostname' => 'any',
                'sn' => 'S5890SB4501307MC1040',
                'status' => 1,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Access Point',
                'brand' => 'Unifi',
                'type' => 'Access Point',
                'hostname' => 'Wifi CORCOM',
                'sn' => 'E063DA52DBED',
                'status' => 1,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Access Point',
                'brand' => 'Unifi',
                'type' => 'Access Point',
                'hostname' => 'Wifi PDPM',
                'sn' => 'E063DA52E545',
                'status' => 1,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Access Point',
                'brand' => 'Unifi',
                'type' => 'Access Point',
                'hostname' => 'Wifi KEU',
                'sn' => 'E063DA52DB3D',
                'status' => 1,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'NVR CCRV SBY',
                'brand' => 'Unifi',
                'type' => 'NVR',
                'hostname' => 'NVR-SBY',
                'sn' => 'B4FBE426EC59',
                'status' => 1,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Access Point',
                'brand' => 'Tp-Link',
                'type' => 'Access Point',
                'hostname' => 'Wifi Ruang Pak Endi',
                'sn' => '13B61503526',
                'status' => 1,
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'LAN Cibubur',
                'brand' => 'Belden',
                'type' => 'UTP',
                'hostname' => 'LAN Cibubur',
                'sn' => '--',
                'status' => 1,
            ),
        );

        Device::insert($array);
    }
}
