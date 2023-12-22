<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
</head>

<body>

    @if ($type === 'create')
        <p>
            Dear Customer, keluhan/request anda sudah kami tindak lanjuti penanganannya pada tiket sebagai berikut:
            <br><br>
            Nomor Tiket: {{ $ticket }} <br>
            Subjek: {!! $subject !!} <br>
            Waktu Laporan: {{ $reportDate }} <br><br>
            Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
            Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
            Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
            Salam, COMIT Care Center.
        </p>
    @elseif($type === 'diagnose')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, saat ini keluhan/request anda sedang kami kaji dengan detail sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Department Terkait: {{ $department }} <br>
                Service: {{ $service }} <br>
                Device: {{ $device }} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, saat ini keluhan/request sedang kami kaji dengan detail sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Department Terkait: {{ $department }} <br>
                Service: {{ $service }} <br>
                Device: {{ $device }} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @elseif($type === 'engineer-assignment')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, saat ini keluhan/request anda sudah kami lakukan eskalasi dengan detail sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Engineer:
                @foreach ($engineer as $engineers)
                    <ul>
                        <li>{{ $engineers->full_name }}</li>
                    </ul>
                @endforeach
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, saat ini keluhan/request sudah kami lakukan eskalasi dengan detail
                sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Engineer:
                @foreach ($engineer as $engineers)
                    <ul>
                        <li>{{ $engineers->full_name }}</li>
                    </ul>
                @endforeach
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @elseif($type === 'engineer-troubleshoot')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, saat ini keluhan/request anda sedang kami tangani dengan rincian sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Penanganan: {!! $content !!} <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, saat ini keluhan/request sedang kami tangani dengan rincian sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Penanganan: {!! $content !!} <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @elseif($type === 'technical-close')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, saat ini keluhan/request anda sudah dapat diselesaikan dengan rincian sebagai berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                RFO: {!! $rfo !!} <br>
                Solusi: {!! $solution !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Durasi penanganan: {{ $handlingDuration }} <br><br>
                Demikian disampaikan, apabila sudah tidak mengalami kendali tiket ini akan kami close. <br><br>
                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, saat ini keluhan/request sudah dapat diselesaikan dengan rincian sebagai
                berikut:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                RFO: {!! $rfo !!} <br>
                Solusi: {!! $solution !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                Durasi penanganan: {{ $handlingDuration }} <br><br>
                Demikian disampaikan, apabila sudah tidak mengalami kendali tiket ini akan kami close. <br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @elseif($type === 'monitoring')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, saat ini keluhan/request anda sedang kami lakukan monitoring:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, saat ini keluhan/request sedang kami lakukan monitoring:
                <br><br>
                Nomor Tiket: {{ $ticket }} <br>
                Subjek: {!! $subject !!} <br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>
                <br><br>
                Demikian disampaikan progress selanjutnya akan dikabari lebih lanjut. <br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @elseif($type === 'closed')
        @if (($dear ?? 'customer') == 'customer')
            <p>
                Dear Customer, apabila sudah tidak mengalami kendala/request untuk {{ $ticket }} :
                {!! $subject !!} lebih lanjut
                maka kami ijin untuk close tiket ini.<br><br>
                Durasi Pembuatan: {{ $diffCreatedDate }} <br><br>
                Durasi Update Terakhir: {{ $diffLastUpdatedDate }} <br><br>

                Salam, COMIT Care Center.
            </p>
        @else
            <p>
                Dear Bapak yang terhormat, untuk ticket dengan nomor {{ $ticket }}, sudah kami close<br><br>
                Salam, COMIT Care Center.
            </p>
        @endif
    @endif

</body>

</html>
