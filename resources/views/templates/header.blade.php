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
            {{-- Kepala Satuan Kerja, Kepala Divisi, Kepala Unit Kerja --}}
            @if (($users->level >= 2) && ($users->level <= 4))
            <div class="mt-1 small text-muted">KEPALA {{ $users->satuanKerja['satuan_kerja'] }}</div>
            
            {{-- Kepala Cabang, Kepala Departemen, Senior Officer, Kepala Bidang, Kepala Bagian, Kepala Operasi Cabang, Officer --}}
            @elseif (($users->level >= 5) && ($users->level <= 11))
            <div class="mt-1 small text-muted">{{ strtoupper($users->satuanKerja['satuan_kerja']) }} | {{ strtoupper($users->departemenTable['departemen']) }}</div>
          
            @elseif ($users->level == 1) 
            <div class="mt-1 small text-muted">Admin</div>

            
            @else
            <div class="mt-1 small text-muted">{{ strtoupper($users->satuanKerja['satuan_kerja']) }} | {{ strtoupper($users->departemenTable['departemen']) }}</div>
          
            @endif
          </div>

          {{-- Kepala Satuan Kerja, Kepala Divisi, Kepala Unit Kerja --}}
          @if (($users->level >= 2) && ($users->level <= 4))
          <i class="fas fa-user-secret fa-2x"></i>
          
          {{-- Kepala Cabang, Kepala Departemen, Senior Officer, Kepala Bidang, Kepala Bagian, Kepala Operasi Cabang, Officer --}}
          @elseif (($users->level >= 5) && ($users->level <= 11))
          <i class="fas fa-user-tie fa-2x"></i>
          
          @elseif ($users->level == 1) 
          <i class="fas fa-user-cog fa-2x"></i>

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

          {{-- Untuk semua user --}}
          @if ($users->level != 1)
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
                  
                  @if ($users->level != 15)
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
                </div>
              </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#modalLaporan">
              <i class="fas fa-file-pdf"></i>
              <span class="nav-link-title ms-1">
                Generate Laporan
              </span>
            </a>
          </li>

          {{-- Untuk Admin --}}
          @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <i class="fas fa-box"></i>
              <span class="nav-link-title ms-1">
                Log Surat
              </span>
            </a>

            <div class="dropdown-menu">
              <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                  <a class="dropdown-item" href="/nomorSurat/allSurat">
                    Semua Surat
                  </a>
                  <a class="dropdown-item" href="/nomorSurat/suratHapus">
                    Surat Terhapus
                  </a>
                </div>
              </div>
            </div>
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
                  <a class="dropdown-item" href="/departemen">
                    List Satuan Kerja dan Departemen
                  </a>
                </div>
              </div>
            </div>
          </li>
          @endif
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

<div class="modal fade" id="modalLaporan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/laporan" method="post">
        @csrf
        <div class="modal-body">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label for="jenis" class="form-label">Jenis Memo</label>
              <select class="form-select" aria-label=".form-select-sm example" name="jenis" id="jenis">
                <option value="masuk">Memo Masuk</option>
                <option value="keluar">Memo Keluar</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="tanggalmulai" class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai">
            </div>
            <div class="form-group mb-3">
              <label for="tanggalakhir" class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" id="tanggalakhir" name="tanggalakhir">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Lanjut</button>
        </div>
      </form>
    </div>
  </div>
</div>