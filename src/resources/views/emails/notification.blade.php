<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
    <style>
        .center {
            text-align: center;
        }

        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <h1 class="center">{{ $title ?? '' }}</h1>
    <table class="table">
        <tbody>

            @php
                $mapTable = [
                    'ticketId' => 'Ticket ID',
                    'subject' => 'Subjek',
                    'department' => 'Department',
                    'service' => 'Layanan',
                    'priority' => 'Prioritas',
                    'ticketType' => 'Jenis Tiket',
                    'ticketCategory' => 'Kategori Tiket',
                    'troubleType' => 'Tipe Gangguan',
                    'device' => 'Device',
                    'incidentTime' => 'Waktu Laporan',
                    'location' => 'Lokasi Kejadian',
                    'informationSource' => 'Sumber Info',
                    'ticketStatus' => 'Status Ticket',
                    'lastProgress' => 'Last Progress',
                ];
            @endphp
            @forelse($table as $key => $value)
                @if (array_key_exists($key, $mapTable))
                    <tr>
                        <td width="40%">{{ $mapTable[$key] ?? '' }}</td>
                        @if ($key == 'subject')
                            <td>{!! $value ?? '' !!}</td>
                            @continue
                        @endif
                        <td>{{ $value ?? '' }}</td>
                    </tr>
                @endif
            @empty
            @endforelse

        </tbody>
    </table>

    @if ($type === 'create')
        <div style="margin: 0px 8px">
            <p>{!! $description ?? '' !!}</p>
        </div>
        @forelse($attachments as $item)
            <img src="{{ $message->embed(str_replace('\\', '/', public_path('storage/' . $item))) }}" alt=""
                style="width: 100%; max-width: 400px; height: 300px; display: block; margin-bottom: 1rem;">
        @empty
        @endforelse
    @elseif($type === 'resume')
        <h3>Resume:</h3>
        <p>{!! $description ?? '' !!}</p>

    @endif

</body>

</html>
