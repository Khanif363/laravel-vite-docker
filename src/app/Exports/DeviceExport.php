<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeviceExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $mainService;
    public function __construct($mainService)
    {
        $this->mainService = $mainService;
    }

    public function query()
    {
        $data = $this->mainService->exportDevice();
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nama Device', 'Merek Device', 'Tipe Device', 'Hostname Device', 'SN Device', 'Status'];
        return $head;
    }
}
