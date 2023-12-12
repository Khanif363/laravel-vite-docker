<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProblemManageExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $request;
    protected $mainService;
    public function __construct($mainService, $request)
    {
        $this->mainService = $mainService;
        $this->request = $request;
    }

    public function query()
    {
        $data = $this->mainService->exportProblemManage($this->request);
        return $data;
    }

    public function headings(): array
    {
        $head = ['Problem Request ID', 'Trouble Ticket ID', 'Subject', 'Content', 'Result', 'Status', 'Last Update'];
        return $head;
    }
}
