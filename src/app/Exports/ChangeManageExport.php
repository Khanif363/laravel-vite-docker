<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChangeManageExport implements FromQuery, WithHeadings, WithMapping
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
        $data = $this->mainService->exportCR($this->request);
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nomor Changes', 'Title', 'Status', 'Tipe Changes', 'Referensi Changes', 'Nama Pembuat', 'PIC Telkomsat', 'PIC Non Telkomsat', 'Lokasi', 'Agenda', 'Tanggal Eksekusi', 'Keterangan', 'Nama Manager', 'Nama GM', 'Status Persetujuan Manager', 'Status Persetujuan GM', 'Tanggal Dibuat', 'Tanggal Terakhir Update', 'Tanggal Closed'];
        return $head;
    }

    public function map($row): array
    {
        return [
            $row->nomor_changes,
            $row->title,
            $row->status,
            is_array($row->type) ? implode(',', $row->type) : $row->type,
            $row->reference,
            $row->creator_name,
            $row->pic_telkomsat,
            $row->pic_nontelkomsat,
            $row->location_name,
            $row->agenda,
            $row->datetime_action,
            $row->content,
            $row->approval1_name,
            $row->approval2_name,
            $row->status_approval1 == 0 ? 'Belum disetujui' : ($row->status_approval1 == 1 ? 'Sudah Disetujui' : ($row->status_approval1 == 2 ? 'Tidak Disetujui' : '')),
            $row->status_approval2 == 0 ? 'Belum disetujui' : ($row->status_approval2 == 1 ? 'Sudah Disetujui' : ($row->status_approval2 == 2 ? 'Tidak Disetujui' : '')),
            !empty($row->created_date) ? Carbon::parse($row->created_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m') : null,
            !empty($row->last_updated_date) ? Carbon::parse($row->last_updated_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m') : null,
            !empty($row->closed_date) ? Carbon::parse($row->closed_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m') : null,
        ];
    }
}
