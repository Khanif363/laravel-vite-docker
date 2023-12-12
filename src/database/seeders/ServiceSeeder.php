<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
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
                "department_id" => 2,
                "name" => "Hardware",
                "status" => 1,
                "category" => null,
            ],
            [
                "department_id" => 2,
                "name" => "Infrastruktur",
                "status" => 1,
                "category" => null,
            ],
            [
                "department_id" => 2,
                "name" => "Aplikasi OSS",
                "status" => 1,
                "category" => null,
            ],
            [
                "department_id" => 2,
                "name" => "Aplikasi BSS",
                "status" => 1,
                "category" => null,
            ],
            [
                "department_id" => 2,
                "name" => "Fasilitas Kerja",
                "status" => 1,
                "category" => null
            ],
            [
                "department_id" => 2,
                "name" => "Laptop",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Seat Management",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "WIFI",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Mini PC",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Monitor",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "CCTV",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Access Device",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "OSS (PRTG, Nagios, NMS, LDAP, VPN)",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Field Support Data Center",
                "status" => 1,
                "category" => ["COMIT IT Tools", "COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "Cloud",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "NAS",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "VM",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "VMIX",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "ZOOM",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Internet User",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Field Support User",
                "status" => 1,
                "category" => ["COMIT IT Tools"]
            ],
            [
                "department_id" => 2,
                "name" => "Routing",
                "status" => 1,
                "category" => ["COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "Provisioning",
                "status" => 1,
                "category" => ["COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "PRTG/Cacti Nagios",
                "status" => 1,
                "category" => ["COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "Test Bandwidth",
                "status" => 1,
                "category" => ["COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "Integration",
                "status" => 1,
                "category" => ["COMIT Fulfillment", "COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "Controller M2M (IOC dan Jasper)",
                "status" => 1,
                "category" => ["COMIT Fulfillment", "COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "Controller SDWAN",
                "status" => 1,
                "category" => ["COMIT Fulfillment", "COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "PE",
                "status" => 1,
                "category" => ["COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "CE",
                "status" => 1,
                "category" => ["COMIT Fulfillment"]
            ],
            [
                "department_id" => 2,
                "name" => "Backhaul",
                "status" => 1,
                "category" => ["COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "BM",
                "status" => 1,
                "category" => ["COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "PRTG",
                "status" => 1,
                "category" => ["COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "Internet Backbone",
                "status" => 1,
                "category" => ["COMIT Assurance"]
            ],
            [
                "department_id" => 2,
                "name" => "3Easy Beyond",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Mantools",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "TsatGo",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Development",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Enhancement",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "DTP",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Dashboard",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "SAP",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "3Easy",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Bro",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Bosnet",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "UAR-UAM",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "BISPRO",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "DWH",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Portal",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Billing Centre",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "3Easy All Module",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "War Room",
                "status" => 1,
                "category" => ["COMIT Application"]
            ],
            [
                "department_id" => 2,
                "name" => "Firewall",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "SIEM",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "SIEM Analysis",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "VA",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "Web Scan Filter",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "End Point",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "Security",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "Security Awareness",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "SSL",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
            [
                "department_id" => 2,
                "name" => "OS Ticket",
                "status" => 1,
                "category" => ["COMIT SOC"]
            ],
        ];

        // Service::insert($array);

        foreach($array as $val) {
            Service::create($val);
        }
    }
}
