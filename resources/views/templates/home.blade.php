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
                            <span class="bg-success text-white avatar">
                                <i class="far fa-check-circle fa-2x"></i>
                            </span>
                        </div>

                        <div class="col">
                            <div class="font-weight-medium">
                            132 Sales
                            </div>
                            <div class="text-muted">
                            12 waiting payments
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
                            <span class="bg-green text-white avatar">

                            </span>
                        </div>

                        <div class="col">
                            <div class="font-weight-medium">
                            78 Orders
                            </div>
                            <div class="text-muted">
                            32 shipped
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
                            <span class="bg-twitter text-white avatar">

                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                            623 Shares
                            </div>
                            <div class="text-muted">
                            16 today
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
                            <span class="bg-facebook text-white avatar">

                            </span>
                        </div>

                        <div class="col">
                            <div class="font-weight-medium">
                            132 Likes
                            </div>
                            <div class="text-muted">
                            21 today
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection