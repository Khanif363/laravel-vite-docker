<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        body {
            margin: auto;
            font-size: 14px;
            font-family: 'calibri';
        }

        html {
            margin: 0px
        }

        /* #bg_image {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        } */

        #page {
            position: absolute;
            /* width: 210mm; */
            width: 800px;
            /* height: 297mm; */
            height: 1131px;
            border-top: 1px solid white;
            z-index: 2;
        }

        #isi {
            margin: 24mm 20mm;
        }

        table {
            border-collapse: collapse;
            /* border: 1px solid black; */
            width: 100%;
            /* page-break-before:auto; */
        }

        th,
        td {
            /* border: 1px solid black; */
            padding: 3px 5px;
            border-spacing: 0;
        }

        .border {
            border: 1px solid black;
        }

        ol {
            margin-left: 10px;
        }

        body {
            background-image: url({{ public_path('assets/img/kopsurat_telkomsat.jpg') }});
            background-repeat: no-repeat;
            background-size: cover;
        }

        .auto-break {
            margin-top: 100px;
            /* Margin atas 100 piksel */
            page-break-before: always !important;
            /* Pindah ke halaman baru sebelum elemen ini */
        }
    </style>
</head>

<body>
    {{-- <img id="bg_image" src="{{ public_path('assets/img/kopsurat_telkomsat.jpg') }}" style="width:800px; page-break-after:always;"> --}}
    <div id="page">
        <div id="isi">
            <table class="isi">
                <tr>
                    <td colspan="3" style="text-align: center;background:#79c7ea;" class="border">
                        {{ $title }}
                    </td>
                </tr>
                <tr>
                    <td class="border">Date</td>
                    <td colspan="2" class="border">{{ date('d F Y', strtotime($table['createdDate'])) }}</td>
                </tr>
                <tr>
                    <td class="border">Time</td>
                    <td colspan="2" class="border">{{ date('H:i', strtotime($table['createdDate'])) . ' WIB' }}</td>
                </tr>
                <tr>
                    <td class="border">&nbsp;</td>
                    <td colspan="2" class="border">{{ $table['changeRequest'] }}</td>
                </tr>

                <tr>
                    <td style="width:210px;" class="border">PIC</td>
                    <td class="border">
                        TELKOMSAT:<br>
                        {{ $table['picTelkomsat'] }}
                    </td>
                    <td class="border">
                        {{ $table['picNonTelkomsat'] }}
                    </td>
                </tr>
                <tr>
                    <td class="border">Type of Maintenance</td>
                    <td colspan="2" class="border">{{ $table['typeOfMaintenance'] }}</td>
                </tr>
                <tr>
                    <td class="border">Priority</td>
                    <td colspan="2" class="border">{{ $table['priority'] }}</td>
                </tr>
                <tr>
                    <td class="border">Location</td>
                    <td colspan="2" class="border">
                        {{ $table['location'] }}
                    </td>
                </tr>
                <tr>
                    <td class="border">AGENDA</td>
                    @php
                        $totalAgendaChar = \Illuminate\Support\Str::length($table['agenda']) + \Illuminate\Support\Str::length($title);
                    @endphp
                    <td colspan="2" class="border">{{ $table['agenda'] }}</td>
                </tr>
                <tr>
                    <td class="border">Date/Time Action</td>
                    <td colspan="2" class="border">
                        {{ date('d F Y / H:i', strtotime($table['dateTimeAction'])) . ' WIB' }}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        @php
                            $currentLength = 0 + ($totalAgendaChar > 62 ? $totalAgendaChar / 62 : 0 * 76);
                        @endphp
                        @php
                            $total = count(preg_split('/(<[^>]*>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE));
                            $totalChar = \Illuminate\Support\Str::length($content);
                            $firstLoop = 0;
                            $waitingTableClose = 0;
                        @endphp
                        @foreach (preg_split('/(<[^>]*>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE) as $key => $cont)
                            @php
                                $matches = preg_match('/<[^>]+>/', $cont);
                                $string = '';
                            @endphp

                            @if ($waitingTableClose && !preg_match('/<\/table>/i', $cont))
                                @continue
                            @elseif ($waitingTableClose && preg_match('/<\/table>/i', $cont))
                                @php
                                    $waitingTableClose = 0;
                                @endphp
                                @continue
                            @endif
                            @if ($matches)
                                @if ($cont == '<br />' || $cont == '<br/>' || $cont == '<br>')
                                    @php
                                        $currentLength += 76;
                                        if ($currentLength <= 2500 && $currentLength >= 2400 && $firstLoop != 1) {
                                            $currentLength -= $currentLength;
                                            $firstLoop = 1;
                                            $cont .= '<div class="auto-break"></div><div
                                                style="position: absolute !important; border: 1px solid black !important; width: 647px; height: 75%; margin-left: -5.5px !important">
                                            </div>';
                                        }
                                    @endphp
                                @endif

                                @if (preg_match('/<table\b[^>]*>/i', $cont))
                                    @php
                                        $waitingTableClose = 1;
                                    @endphp
                                    @continue
                                @endif

                                {!! $cont !!}
                            @elseif (preg_match('/\n/', $cont))
                                @php
                                    $currentLength += 76;
                                    $currentLength += \Illuminate\Support\Str::length($cont) - 2;

                                    if ($currentLength <= 2500 && $currentLength >= 2400 && $firstLoop != 1) {
                                        $currentLength -= $currentLength;
                                        $firstLoop = 1;
                                        $cont .= '<div class="auto-break"></div><div
                                            style="position: absolute !important; border: 1px solid black !important; width: 647px; height: 75%; margin-left: -5.5px !important">
                                        </div>';
                                    }
                                @endphp
                                {!! $cont !!}
                            @elseif ($cont == ' ' || $cont == '&nbsp;')
                                @php
                                    $currentLength++;
                                @endphp
                                {!! $cont !!}
                            @else
                                @foreach (str_split($cont) as $keyChar => $char)
                                    @if (
                                        $currentLength > 0 &&
                                            (($firstLoop != 1 && $currentLength <= 2500 && $currentLength >= 2400) || $currentLength % 4300 == 0))
                                        @php
                                            if ($currentLength <= 2500) {
                                                $currentLength -= $currentLength;
                                                $firstLoop = 1;
                                            }
                                            $string .= '<div class="auto-break"></div><div
    style="position: absolute !important; border: 1px solid black !important; width: 647px; height: 75%; margin-left: -5.5px !important">
</div>';
                                        @endphp
                                    @elseif ($key == 0)
                                        @php
                                            $string .=
                                                '<div
                                        style="position: absolute !important; border: 1px solid black !important; width: 647px !important; height: ' .
                                                (($key == 0 && $gm ? 3 : 0) + 50 - $totalAgendaChar / 62) .
                                                '% !important; margin-left: -5.5px !important; margin-top: -4.5px !important">
                                    </div>';
                                        @endphp
                                    @endif
                                    @php
                                        $currentLength++;
                                        $string .= $char;
                                    @endphp
                                @endforeach
                                {!! $string !!}
                            @endif
                        @endforeach

                        @php
                            $totalNumber = 30;
                            $multipleNumber = [];

                            // Inisialisasi array dengan nilai awal 6000
                            $multipleNumber[] = 4300;

                            for ($i = 1; $i <= $totalNumber; $i++) {
                                $multipleNumber[] = $multipleNumber[$i - 1] + 4300;
                            }
                        @endphp

                        @foreach ($multipleNumber as $numKey => $multiple)
                            @if (
                                ($firstLoop != 1 && $currentLength <= 4000 && $currentLength > 1050) ||
                                    (!$loop->last && ($currentLength >= $multiple + 3000 && $currentLength < $multipleNumber[$numKey + 1])))
                                <div class="auto-break"></div>
                                <div
                                    style="position: absolute !important; border: 1px solid black !important; width: 647px; height: 75%; margin-left: -5.5px !important">
                                </div>
                            @break
                        @endif
                    @endforeach



                    {{-- <table
                            style="width: 100%; table-layout: fixed; max-height: 500px; overflow: hidden; border-collapse: collapse;">
                            <tr>
                                <th style="width: 33%; border: 1px solid black;">Nama</th>
                                <th style="width: 33%; border: 1px solid black;">Usia</th>
                                <th style="width: 33%; border: 1px solid black;">Kota</th>
                            </tr>
                            <tr>
                                <td style="width: 33%; border: 1px solid black;">John Doe</td>
                                <td style="width: 33%; border: 1px solid black;">30</td>
                                <td style="width: 33%; border: 1px solid black;">New York</td>
                            </tr>
                            <!-- ...Tambahkan baris-baris tabel lainnya... -->
                            <!-- Tambahkan baris dengan style untuk pemisahan halaman -->
                            <tr style="border: none; page-break-before: always;">
                                <td style="height: 100px !important"></td>
                            </tr>
                            <tr>
                                <td style="width: 33%; border: 1px solid black;">John Doe</td>
                                <td style="width: 33%; border: 1px solid black;">30</td>
                                <td style="width: 33%; border: 1px solid black;">New York</td>
                            </tr>
                        </table> --}}



                    <div
                        style="position: absolute; {{ $gm ? 'bottom: 200px' : 'bottom: 100px' }}; border-top: 1px solid black; width: 647px; margin-left: -5.5px !important">
                        <div style="padding:5px;">
                            Bogor, {{ date('d F Y', strtotime($table['createdDate'])) }}
                        </div>
                        @php
                            $jmlslice = 0;
                            if (!empty($engineer)) {
                                $jmlslice++;
                            }
                            if (!empty($manager)) {
                                $jmlslice++;
                            }
                            $width = 100;
                            if ($jmlslice > 0) {
                                $width = $width / $jmlslice;
                            }
                        @endphp
                        <div style="{{ !$gm ? 'height: 20%' : '' }}">
                            @if (!empty($engineer))
                                <div
                                    style="text-align:center;float:left;padding-top:30px; {{ 'width:' . $width . '%' }}">
                                    <div style="height:30px;text-align:center;font-weight:bold;">
                                        {{ $inisial_engineer }}
                                    </div>
                                    <u>{{ $engineer }}</u>
                                    <br>
                                    {{ $jabatan_engineer }}
                                </div>
                            @endif

                            @if (!empty($manager))
                                <div
                                    style="text-align: center;float:left;padding-top:30px; {{ 'width:' . $width . '%' }}">
                                    <div style="height:30px;text-align:center;font-weight:bold;">
                                        {{ $status_approval1 == 1 ? $inisial_manager : ' ' }}
                                    </div>
                                    <u>{{ $manager }}</u>
                                    <br>
                                    {{ $jabatan_manager }}
                                </div>
                            @endif
                        </div>
                        <br>
                        <br>
                        @if ($gm)
                            <div style="text-align:center;">
                                Mengetahui dan Menyetujui
                            </div>
                            <div style="height:35px;text-align:center;padding-top:30px;font-weight:bold;">
                                {{ $status_approval2 == 1 ? $inisial_gm : ' ' }}
                            </div>
                            <div style="height:30px;text-align:center;">
                                <u>{{ $gm }}</u>
                                <br>
                                {{ $jabatan_gm }}
                            </div>
                            <br>
                        @endif
                    </div>

                </td>
            </tr>
        </table>
    </div>
</div>
</body>

</html>
