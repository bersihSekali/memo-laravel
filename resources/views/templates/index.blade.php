<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Pencatatan Memo</title>
    <!-- CSS files -->
    <link rel="icon" type="image/x-icon" href="{{url('assets/img/bcasTab.jpeg')}}">
    <link href="{{url('/assets/fontawesome/css/all.css')}}" rel="stylesheet">
    <link href="{{url('/assets/fontawesome/css/fontawesome.css')}}" rel="stylesheet">
    <link href="{{url('/assets/fontawesome/css/brands.css')}}" rel="stylesheet">
    <link href="{{url('/assets/fontawesome/css/solid.css')}}" rel="stylesheet">
    <link href="{{url('assets/dist/css/tabler.min.css')}}" rel="stylesheet"/>
    <link href="{{url('assets/dist/css/tabler-flags.min.css')}}" rel="stylesheet"/>
    <link href="{{url('assets/dist/css/tabler-payments.min.css')}}" rel="stylesheet"/>
    <link href="{{url('assets/dist/css/tabler-vendors.min.css')}}" rel="stylesheet"/>
    <link href="{{url('assets/dist/css/demo.min.css')}}" rel="stylesheet"/>

    <script defer src="{{url('assets/fontawesome/js/all.js')}}"></script> <!--load all styles -->
    <script defer src="{{url('assets/fontawesome/js/brands.js')}}"></script>
    <script defer src="{{url('assets/fontawesome/js/solid.js')}}"></script>
    <script defer src="{{url('assets/fontawesome/js/fontawesome.js')}}"></script>
  </head>
  <body >
    <div class="page">
      @include('templates.header')

      <div class="page-wrapper">
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{url('assets/dist/js/tabler.min.js')}}" defer></script>
    <script src="{{url('assets/dist/js/demo.min.js')}}" defer></script>
  </body>
</html>