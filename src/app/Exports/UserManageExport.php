<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserManageExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $mainService;
    public function __construct($mainService)
    {
        $this->mainService = $mainService;
    }

    public function query()
    {
        $data = $this->mainService->exportUserManage();
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nama Lengkap', 'Email', 'Department', 'Role', 'Username', 'Nik', 'Enable', 'Terakhir Login'];
        return $head;
    }
}
