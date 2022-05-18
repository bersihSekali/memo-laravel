@extends('templates.index')

@section('content')
    <?php 
        date_default_timezone_set("Asia/Jakarta");
        $jam = date('H:i');

        if ($jam > '05:30' && $jam < '10:00') {
            $salam = 'Pagi';
        } elseif ($jam >= '10:00' && $jam < '15:00') {
            $salam = 'Siang';
        } elseif ($jam < '18:00') {
            $salam = 'Sore';
        } else {
            $salam = 'Malam';
        }
    ?>

    <h1>Selamat {{ $salam }}, {{ strtoupper($users->name) }}</h1>
@endsection