<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $mainService;
    public function __construct($mainService)
    {
        $this->mainService = $mainService;
    }

    public function query()
    {
        $data = $this->mainService->exportDepartment();
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nama Department', 'Status', 'Service Desk', 'Core'];
        return $head;
    }
}
