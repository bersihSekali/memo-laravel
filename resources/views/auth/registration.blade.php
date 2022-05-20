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
            <label for="satuan_kerja" class="form-label">Satuan Kerja</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja" id="satuan_kerja">
              <option selected> ---- </option>
              @foreach ($satuanKerja as $item)
              <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label for="departemen" class="form-label">Departemen</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen" id="departemen">
              <option selected> ---- </option>
              @foreach ($departemen as $item)
              <option value="{{$item['id']}}">{{$item['departemen']}}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label for="level" class="form-label">Level</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="level" id="level">
              <option selected> ---- </option>
              <option value="1">Admin</option>
              <option value="2">Head</option>
              <option value="3">Staff</option>
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
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="{{url('assets/dist/js/tabler.min.js')}}" defer></script>
  <script src="{{url('assets/dist/js/demo.min.js')}}" defer></script>
</body>

</html>