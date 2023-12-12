<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResumeClosingExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $mainService;
    public function __construct($mainService)
    {
        $this->mainService = $mainService;
    }

    public function query()
    {
        $data = $this->mainService->exportResume();
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nama Department', 'Resume', 'Status'];
        return $head;
    }
}
