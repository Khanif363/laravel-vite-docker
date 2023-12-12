<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
    <style>
        .head {
            height: 40px;
            background-color: #C0C0C0
        }

        .space-dot {
            width: 150px;
            /* Sesuaikan lebar sesuai kebutuhan */
            display: inline-block;
            text-align: left;
            margin-right: 10px;
            /* Jarak antara teks dan titik */
        }

        .first-column {
            width: 3%;
        }
    </style>
</head>

<body>

    {{-- <p>
        Dh,<br>
        Ticket dengan nomor {{ '[' }} {{ $ticket }} {{ ']' }}
        {{ $dataTicket->lastProgress->update_type === 'Closed' ? 'telah kami closed.' : 'sedang kami proses.' }}<br>
        Berikut detailnya:
        <br><br><br>
    </p> --}}
    <p>
        Dh,<br>
        Berikut kami sampaikan rangkaian progress ticket yang
        {{ $dataTicket?->lastProgress?->update_type === 'Closed' ? 'telah kami closed.' : 'sedang kami proses.' }}<br><br>
    <div class="space-dot"><span>Nomor Ticket</span></div><span>:</span> {{ $dataTicket->nomor_ticket }}<br>
    <div class="space-dot">Subjek</div><span>:</span> {!! $dataTicket->subject !!}<br>
    <div class="space-dot">Durasi</div><span>:</span> {{ $handlingDuration }}<br>

    <br><br><br>
    </p>

    {{-- <p>
    <h5 style="font-weight: 500">Open</h5>
    Dibuat pada: {{ $dataTicket->created_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }} <br>
    </p>
    @foreach ($progressTicket as $progress)
        @if ($progress->update_type === 'Diagnose')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }} <br>
                Department: {{ $department }} <br>
                Layanan: {{ $service }} <br>
                Device: {{ $device }} <br>
                Detail: <br>
                {!! $progress->content ?? '' !!}<br><br>
            </p>
        @elseif($progress->update_type === 'Engineer Assignment')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }} <br>
                Engineer:
                <ul class="ml-4 list-disc">
                    @forelse ($progress->engineerAssignment->engineer as $engineer)
                        <li>{{ $engineer->user->full_name }}</li>
                    @empty
                    @endforelse
                </ul>
                <br><br>
            </p>
        @elseif($progress->update_type === 'Pending')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                Pending: {{ $progress->pending?->pending_by ?? null }} <br>
                Alasan: {!! $progress->pending?->reason ?? '' !!} <br>
                <br><br>
            </p>
        @elseif($progress->update_type === 'Dispatch')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                Dari Department: {{ $progress->dispatch->department_from->name ?? '' }} <br>
                Ke Department: {{ $progress->dispatch->department_to->name ?? '' }} <br>
                Detail: <br>
                {!! $progress->content ?? '' !!}
                <br><br>
            </p>
        @elseif($progress->update_type === 'Engineer Troubleshoot')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                Penanganan: {!! $progress->content ?? '' !!} <br><br>
            </p>
        @elseif($progress->update_type === 'Technical Close')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            @php
                $techClose = $progress->technicalClose()->first();
                $handlingDuration = date_diff($progress->inputed_date, $dataTicket->created_date);
                $totalHandlingDuration = $handlingDuration ? ($handlingDuration->d != 0 ? "$handlingDuration->d Hari, " : '') . ($handlingDuration->h != 0 ? "$handlingDuration->h Jam, " : '') . ($handlingDuration->i != 0 ? "$handlingDuration->i Menit, " : '') . ($handlingDuration->s != 0 ? "$handlingDuration->s Detik" : '') : '';
            @endphp
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                RFO: {!! $techClose?->rfo !!} <br>
                Solusi: {!! $dataTicket?->catalog?->information ?? null !!} <br>
                Durasi penanganan: {{ $totalHandlingDuration }} <br><br>
            </p>
        @elseif($progress->update_type === 'Monitoring')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                Detail: {!! $progress->content ?? '' !!} <br><br>
            </p>
        @elseif($progress->update_type === 'Closed')
            <h5 style="font-weight: 500">{{ $progress->update_type }}</h5>
            <p>
                Date/Time: {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                <br>
                Detail: {!! $progress->content ?? '' !!} <br><br>
            </p>
        @endif
    @endforeach --}}


    <table border="1" style="width:100%">
        <tr class="head">
            <th class="first-column">No.</th>
            <th>Waktu</th>
            <th>Keterangan</th>
            <th>Status</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>{{ $dataTicket->created_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}</td>
            <td>Dibuat oleh: {{ $dataTicket?->user?->full_name }}</td>
            <td>Open</td>
        </tr>
        @foreach ($progressTicket as $key => $progress)
            @if ($progress->update_type != 'Pending')
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration + 1 }}</td>
                    <td>{{ $progress?->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}</td>
                    @if ($progress->update_type == 'Pending')
                        <td>Pending {{ $progress?->pending->pending_by }}
                            @if ($progress?->content)
                                <br>{!! $progress?->content !!}
                            @endif
                        </td>
                    @elseif ($progress->update_type == 'Engineer Assignment')
                        <td>Engineer:<br>
                            @forelse ($progress?->engineerAssignment?->engineer as $engineer)
                                <li>{{ $engineer?->user?->full_name }}</li>
                            @empty
                            @endforelse
                            @if ($progress?->content)
                                <br>{!! $progress?->content !!}
                            @endif
                        </td>
                    @elseif ($progress->update_type == 'Technical Close')
                        <td>
                            @forelse ($dataTicket?->catalogs as $catalog)
                                <li>{!! $catalog?->information !!}</li>
                            @empty
                                <div style="text-align: center;">-</div>
                            @endforelse
                        </td>
                    @else
                        <td>{!! $progress?->content ?? '-' !!}</td>
                    @endif
                    <td>{{ $progress->update_type }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    <p style="margin-top: 100px">
        Terimakasih.<br>
        --<br>
        Comit Care Center.<br>
        Compliance, Operation, Measurement of IT.<br>
    </p>
    <img src="{{ asset('assets/img/telkomsat_logo.png') }}" alt="Telkomsat" width="100px">
    <p>
        M: +62 812-2000-4022<br>
        E: <a href="mailto:comitcc@telkomsat.co.id">comitcc@telkomsat.co.id</a><br>
    </p>


</body>

</html>
