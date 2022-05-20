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
    
    <div class="row row-cards">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-info text-white avatar">
                                <i class="far fa-file fa-2x"></i>
                            </span>
                        </div>

                        <div class="col">
                            <div class="fs-3 font-weight-medium">
                                {{ $countTotal }}
                            </div>
                            <div class="text-muted">
                                Total
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar">
                                <i class="far fa-check-circle fa-2x"></i>
                            </span>
                        </div>

                        <div class="col">
                            <div class="fs-3 font-weight-medium">
                                {{ $countApproved }}
                            </div>
                            <div class="text-muted">
                                Disetujui
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-danger text-white avatar">
                                <i class="far fa-times-circle fa-2x"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="fs-3 font-weight-medium">
                                {{ $countRejected }}
                            </div>
                            <div class="text-muted">
                                Ditolak
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-secondary text-white avatar">
                                <i class="fas fa-spinner fa-pulse fa-2x"></i>
                            </span>
                        </div>

                        <div class="col">
                            <div class="fs-3 font-weight-medium">
                                {{ $countPending }}
                            </div>
                            <div class="text-muted">
                                Pending
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection