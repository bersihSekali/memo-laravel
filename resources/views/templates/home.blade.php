@extends('templates.index')

@section('content')
{{-- @if ($users->level == 1)
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function () {
        $(".form-selectgroup-input").change(function() {
            var tipe = $(".form-selectgroup-input:checked").val()
            if (tipe == 1) {
                $('.tanggal').show(1000)
            } else {
                $('.tanggal').hide(500)
                $('#tanggalmulai').val('');
                $('#tanggalakhir').val('');
            }
        })

        $('#log').click(function() { 
            $('.form-user').toggle();
        });
    });
    </script>
@endif --}}

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