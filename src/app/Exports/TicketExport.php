<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;



class TicketExport implements FromQuery, WithHeadings, WithMapping
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
        $data = $this->mainService->exportTicket($this->request);
        return $data;
    }

    public function headings(): array
    {
        $head = ['Nomor Ticket', 'Prioritas', 'Tipe', 'Department', 'Service', 'Subject', 'Update Terakhir', 'Diupdate Oleh', 'Dibuat Oleh', 'Sumber Info', 'Status', 'keterangan Masalah', 'Durasi Response', 'Durasi Penyelesaian', 'Solusi', 'Evaluasi', 'Visit Lokasi', 'Detail Sumber Info', 'Nama Pelapor', 'Telp Pelapor', 'Email Pelapor', 'Tanggal Create', 'Tanggal Pelaporan', 'Tanggal Terakhir Update', 'Tanggal Technical Close', 'Tanggal Closed'];
        return $head;
    }

    public function map($row): array
    {
        $time_duration = ['time_duration_response' => $row->time_duration_response, 'time_duration_recovery' => $row->time_duration_recovery];
        foreach ($time_duration as $key => $resultFloor) {
            if ($resultFloor >= 0) {
                $days = floor($resultFloor / 86400);
                $resultFloor = $resultFloor % 86400;

                // Menghitung jam
                $hours = floor($resultFloor / 3600);
                $resultFloor = $resultFloor % 3600;

                // Menghitung menit
                $minutes = floor($resultFloor / 60);
                $seconds = $resultFloor % 60;

                $time_duration[$key] = ($days != 0 ? $days . " Hari" : '') . ($hours != 0 ? ($days != 0 ? ", " : '') . $hours . " Jam" : '') . ($minutes != 0 ? ($hours != 0 ? ", " : '') . $minutes . " Menit" : '') . ($seconds != 0 ? ($minutes != 0 ? ", " : '') . $seconds . " Detik" : '');
            } else {
                $time_duration[$key] = '-';
            }
        }

        return [
            $row->nomor_ticket ?? null,
            $row->priority ?? null,
            $row->type ?? null,
            $row->department_name ?? null,
            $row->service_name ?? null,
            $row->subject ?? null,
            $row->update_type ?? null,
            $row->progress_inputer ?? null,
            $row->creator_name ?? null,
            $row->source_info_trouble ?? null,
            $row->status ?? null,
            strip_tags($row->problem) ?? null,
            $time_duration['time_duration_response'] ?? null,
            $time_duration['time_duration_recovery'] ?? null,
            $row->solution ?? null,
            $row->evaluation ?? null,
            $row->visit_location ?? null,
            $row->detail_info ?? null,
            $row->ticket_info_name ?? null,
            $row->number_phone ?? null,
            $row->email ?? null,
            $row->created_date ? Carbon::parse($row->created_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm A') : null,
            $row->event_datetime ? Carbon::parse($row->event_datetime)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm A') : null,
            $row->last_updated_date ? Carbon::parse($row->last_updated_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm A') : null,
            $row->technical_closed_date ? Carbon::parse($row->technical_closed_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm A') : null,
            $row->closed_date ? Carbon::parse($row->closed_date)->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm A') : null,
        ];
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //     ];
    // }
}
