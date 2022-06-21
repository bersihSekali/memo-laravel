@extends('templates.index')

@section('content')
{{-- @if ($users->level == 1)
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
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

<h1 class="h1 mb-4">Selamat {{ $salam }}, {{ strtoupper($users->name) }}</h1>

<div class="row">
    <div class="col-sm-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Surat Keluar Tahun Ini</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 my-3">{{$countSelesaiKeluar}}/{{$countTotalKeluar}}</div>
                    <div class="h5 ms-1 my-3">Surat Telah Terbit</div>
                </div>
                <div class="d-flex mb-2">
                    <div>Penyelesaian</div>
                    <div class="ms-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            @if ($countTotalKeluar)
                            {{intval($countSelesaiKeluar/$countTotalKeluar*100)}}%
                            @else
                            Belum ada surat Keluar
                            @endif
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{$countTotalKeluar ? $countSelesaiKeluar/$countTotalKeluar*100 : 0 }}%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                        <span class="visually-hidden">75% Complete</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-yellow text-white avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 9v2m0 4v.01"></path>
                                <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            Butuh Persetujuan Anda
                        </div>
                        <div class="text-muted">
                            {{$countOtor}} surat
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-blue text-white avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 11l2 2l4 -4"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            Menunggu Persetujuan
                        </div>
                        <div class="text-muted">
                            {{$countBelumSelesaiKeluar}} surat
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Surat Masuk Tahun Ini</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 my-3">{{$countSelesaiMasuk}}/{{$countTotalMasuk}}</div>
                    <div class="h5 ms-1 my-3">Surat Telah Dibaca</div>
                </div>
                <div class="d-flex mb-2">
                    <div>Penyelesaian</div>
                    <div class="ms-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            @if ($countTotalMasuk)
                            {{intval($countSelesaiMasuk/$countTotalMasuk*100)}}%
                            @else
                            Belum ada surat masuk
                            @endif
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{$countTotalMasuk ? intval($countSelesaiMasuk/$countTotalMasuk*100) : 0 }}%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                        <span class="visually-hidden">75% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection