<header class="navbar navbar-expand-md navbar-light d-print-none">
  <div class="container-xl">
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="/">
        <img src="{{url('assets/img/bcasLogo.png')}}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
      </a>
    </h1>
    <div class="navbar-nav flex-row order-md-last">
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <div class="d-none d-xl-block pe-2">
            <div class="text-end">{{ strtoupper($users->name) }}</div>
            <div class="mt-1 small text-muted">{{ $users->satuanKerja['satuan_kerja'] }} | {{ $users->departemenTable['departemen'] }}</div>
          </div>

          @if ($users->level == "admin")
          <i class="fas fa-users-cog fa-2x"></i>
          @elseif ($users->level == "head")
          <i class="fas fa-user-plus fa-2x"></i>
          @else
          <i class="fas fa-user fa-2x"></i>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <form action="/logout" method="post">
            @csrf
            <button type="submit" class="dropdown-item">
              Logout
            </button>
          </form>
        </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="#" class="dropdown-item">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar navbar-light">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/">
              <i class="fas fa-home"></i>
              <span class="nav-link-title ms-1">
                Home
              </span>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <i class="fas fa-users"></i>
              <span class="nav-link-title ms-1">
                Admin
              </span>
            </a>
            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="/listuser">
                    Daftar Pengguna
                  </a>
                  <a class="dropdown-item" href="/registration">
                    Tambah Pengguna
                  </a>
                </div>
              </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <i class="fas fa-envelope"></i>
              <span class="nav-link-title ms-1">
                Surat
              </span>
            </a>
            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="/nomorSurat/create">
                    Registrasi Surat
                  </a>
                  <a class="dropdown-item" href="/otorisasi">
                    Otorisasi Surat
                  </a>
                  <a class="dropdown-item" href="/suratMasuk">
                    Surat Masuk
                  </a>
                  <a class="dropdown-item" href="">
                    Disposisi Masuk
                  </a>
                  <a class="dropdown-item" href="/suratKeluar">
                    Surat Keluar
                  </a>
                  <a class="dropdown-item" href="">
                    Generate Laporan
                  </a>
                </div>
              </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>