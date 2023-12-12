<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
    <style>
        .table {
            border: 1px solid;
            border-collapse: collapse;
        }

        .table>tbody>tr {
            border: 1px solid;
        }

        .table>tbody>tr>td {
            border: 1px solid;
        }

        .float-right {
            float: right;
        }

        .clear {
            clear: both;
        }

        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('assets/img/telkomsat_logo.png') }}" alt="" class="float-right"
        style="width: 250px; height: 100px;" />
    <div class="clear"></div>
    <table class="table w-100">
        <tr>
            <td colspan="2" class="text-center" style="font-size: 1.5rem;">{{ $title ?? '' }}</td>
        </tr>

        @php
            $mapTable = [
                'changeRequest' => 'CHANGE REQUEST',
                'date' => 'DATE',
                'time' => 'TIME',
                'location' => 'LOCATION',
                'pic' => 'PIC',
                'typeOfMaintenance' => 'TYPE OF MAINTENANCE',
                'agenda' => 'AGENDA',
                'dateTimeAction' => 'DATE/TIME ACTIONS',
            ];
        @endphp
        @forelse($table as $key => $value)
            @if (array_key_exists($key, $mapTable))
                <tr>
                    <td class="text-center" width="30%">{{ $mapTable[$key] ?? '' }}</td>
                    <td>&nbsp;{{ $value ?? '' }}</td>
                </tr>
            @endif
        @empty
        @endforelse

        <tr>
            <td colspan="2">
                <div style="margin: 5px">&nbsp;{!! $content ?? '' !!}</div>
                <br>
                <div>
                    @forelse($attachments as $item)
                        <img src="{{ str_replace('\\', '/', public_path('storage/' . $item)) }}" alt=""
                            style="width: 100%; max-height: 400px; height: 100%; display: block;">
                    @empty

                        &nbsp;
                    @endforelse

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;Bogor, {{ $date ?? date('d F Y') }}</p>
                <table class="text-center w-100">
                    <tr>
                        <td class="text-underline" width="50%">
                            <br><br><br><br>
                            {{-- @foreach ($engineer as $engineers)
                                {{ $engineers->full_name ?? '' }}@if (!$loop->last), @endif
                            @endforeach --}}
                            {{ $engineer }}
                        </td>
                        <td class="text-underline">
                            <br><br><br><br>
                            {{ $manager ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            Engineer
                        </td>
                        <td>
                            Manager IT & Cyber Security
                        </td>
                    </tr>
                    @if ($gm)
                        <tr>
                            <td colspan="2" width="50%">
                                <br><br>
                                Mengetahui & Menyetujui
                                <br><br><br><br><br>
                                {{ $gm ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                GM Information Technology
                            </td>
                        </tr>
                    @endif
                    @if (!$gm)
                        <tr>
                            <td>
                                <br><br>
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
