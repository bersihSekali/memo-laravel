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
          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-logout">Logout</a>
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

          @if ($users->level == 'admin')
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
                  <a class="dropdown-item" href="/departemen">
                    List Satuan Kerja dan Departemen
                  </a>
                </div>
              </div>
          </li>
          @endif

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <i class="fas fa-envelope"></i>
              <span class="nav-link-title ms-1">
                Keluar
              </span>
            </a>

            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="/nomorSurat">
                    Registrasi Surat
                  </a>
                  @if ($users->level == 'head')
                  <a class="dropdown-item" href="/otorisasi">
                    Otorisasi Surat
                  </a>
                  @endif

                  <a class="dropdown-item" href="/suratKeluar">
                    Surat Keluar
                  </a>
                </div>
              </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <i class="fas fa-envelope"></i>
              <span class="nav-link-title ms-1">
                Masuk
              </span>
            </a>

            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="/suratMasuk">
                    Surat Masuk
                  </a>
                  <a class="dropdown-item" href="/disposisi">
                    Disposisi Masuk
                  </a>
                </div>
              </div>
          </li>
          <li class="nav-item">
            <a class="dropdown-item" href="/laporan">
              Generate Laporan
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-logout" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-danger"></div>

      <div class="modal-body text-center py-4">
        <h3>Apakah yakin ingin logout</h3>
      </div>

      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col">
              <a href="#" class="btn w-100" data-bs-dismiss="modal">
                Tidak
              </a>
            </div>

            <div class="col">
              <form action="/logout" method="post">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>