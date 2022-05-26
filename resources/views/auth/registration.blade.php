<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>{{ $title }}</title>
  <!-- CSS files -->
  <link href="{{url('assets/dist/css/tabler.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-flags.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-payments.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-vendors.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/demo.min.css')}}" rel="stylesheet" />
</head>

<body class=" border-top-wide border-primary d-flex flex-column">
  <div class="page page-center">
    <div class="container-tight">
      <div class="text-center mb-2">
        <a href="" class="navbar-brand navbar-brand-autodark"><img src="{{url('assets/img/bcasLogo.png')}}" height="36" alt=""></a>
      </div>

      <form class="card card-md" action="/registration" method="post" class="row g-3 needs-validation">
        @csrf

        <div class="card-body">
          @if(session()->has('error'))
          <div class="alert alert-danger" role="alert">
            {{ session('error') }}
          </div>
          @endif

          <h2 class="card-title text-center">Registrasi Pengguna Baru <br> Pencatatan Memo Internal BCASyariah</h2>

          <div class="mb-2">
            <label class="form-label">Username</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Username" autocomplete="off">

            @error('name')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>

          <div class="mb-2">
            <label for="level" class="form-label">Level</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="level" id="level">
              <option selected> ---- </option>
              @foreach ($level as $item)
              <option value="{{$item['id']}}">{{$item['level']}}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
              <label for="satuan_kerja_tujuan" class="form-label">Satuan Kerja Tujuan</label>
              <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja_tujuan" id="satuan_kerja_tujuan">
                  <option selected> ---- </option>
                  @foreach ($satuanKerja as $item)
                  <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label for="departemen_tujuan" class="form-label">Department Tujuan</label>
              <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen_tujuan" id="departemen_tujuan">
                  <option value=""> ---- </option>
              </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group input-group-flat">
              <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="password">
              <span class="input-group-text">
                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                  <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                </a>
              </span>
            </div>
          </div>

          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Sign in</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <script>
      jQuery(document).ready(function(){
          jQuery('#satuan_kerja_tujuan').change(function(){
              var skid = jQuery(this).val();
              alert(skid);
              jQuery.ajax({
                  url: '/getSatuanKerja',
                  type: 'post',
                  data: 'skid='+skid+'&_token={{csrf_token()}}',
                  success: function(result){
                      jQuery('#departemen_tujuan').html(result)
                  }
              });
          });
      });
  </script>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="{{url('assets/dist/js/tabler.min.js')}}" defer></script>
  <script src="{{url('assets/dist/js/demo.min.js')}}" defer></script>

</body>
</html>