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
<p>
    Dear Customer, apabila sudah tidak mengalami kendala/request untuk {{ $ticket }} : {!! $subject !!} lebih lanjut
    maka kami ijin untuk close tiket ini. <br><br>
    Mohon kesedian anda untuk berpartisipasi dalam survei untuk menjawab pertanyaan. Kuesioner survei dapat diakses
    <a href="{{ route('rating', ['token' => $token]) }}">Disini</a> <br><br>
    Atas partisipasi dan kerjasamanya kami ucapkan terima kasih
    Salam, COMIT Care Center.
</p>
</body>

</html>
