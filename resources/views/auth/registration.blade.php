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
  <link href="{{url('assets/dist/css/select2.min.css')}}" rel="stylesheet" />

  <!-- Styles Select2  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <!-- Or for RTL support -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />


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
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Username" autocomplete="off" required>

            @error('name')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>

          <div class="mb-2">
            <label for="level" class="form-label">Level</label>
            <select class="form-select mb-3" aria-label=".form-select-sm example" name="level" id="level" data-width="100%" required>
              <option selected disabled> ---- </option>
              @foreach ($level as $item)
              <option value="{{$item['id']}}">{{$item['level']}}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3" id="input_satuan_kerja">
            <label for="satuan_kerja" class="form-label">Satuan Kerja</label>
            <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja" id="satuan_kerja" data-width="100%" required>
              <option selected disabled> ---- </option>
              @foreach ($satuanKerja as $item)
              <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3" id="input_departemen">
            <label for="departemen" class="form-label">Department</label>
            <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen" id="departemen" data-width="100%">
              <option selected disabled> ---- </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group input-group-flat">
              <input type="password" class="form-control @error('name') is-invalid @enderror" placeholder="Password" autocomplete="off" name="password" required>
              <span class="input-group-text">
                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                </a>
              </span>
            </div>
            @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
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
    jQuery(document).ready(function() {
      // Hide input departemen
      jQuery('#input_departemen').hide();
      jQuery('#input_satuan_kerja').hide();

      // get value level id
      jQuery('#level').change(function() {
        var lid = jQuery(this).val();
        if (lid == 1) {
          jQuery('#input_departemen').hide();
          jQuery('#input_satuan_kerja').hide();
        } else if (lid > 2) {
          jQuery('#input_satuan_kerja').show();
          jQuery('#input_departemen').show();
        } else {
          jQuery('#input_satuan_kerja').show();
          jQuery('#input_departemen').hide();
        }
      });

      jQuery('#satuan_kerja').change(function() {
        var skid = jQuery(this).val();
        jQuery.ajax({
          url: '/getSatuanKerja',
          type: 'post',
          data: 'skid=' + skid + '&_token={{csrf_token()}}',
          success: function(result) {
            jQuery('#departemen').html(result)
          }
        });
      });
    });
  </script>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="{{url('assets/dist/js/tabler.min.js')}}" defer></script>
  <script src="{{url('assets/dist/js/demo.min.js')}}" defer></script>
  <script src="{{url('assets/dist/js/select.js')}}"></script>
  <script src="{{url('assets/dist/js/select2.min.js')}}"></script>

  <!-- Scripts Select2 Bootstrap-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>