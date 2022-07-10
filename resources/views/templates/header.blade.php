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
            @if (($users->level >= 2) && ($users->level <= 4)) <div class="mt-1 small text-muted text-end">KEPALA {{ $users->satuanKerja->satuan_kerja }}
          </div>

          @elseif (($users->satuanKerja['grup'] == 5) && ($users->levelTable['golongan'] == 6))
          <div class="mt-1 small text-muted text-end">KEPALA {{ strtoupper($users->satuanKerja['satuan_kerja']) }}</div>

          @elseif (($users->cabang) && ($users->level == 5))
          <div class="mt-1 small text-muted text-end">KEPALA CABANG {{ strtoupper($users->cabangTable['cabang']) }}</div>

          @elseif (($users->cabang) && ($users->level == 9))
          <div class="mt-1 small text-muted text-end">KEPALA OPERASIONAL CABANG {{ strtoupper($users->cabangTable['cabang']) }}</div>

          {{-- Kepala Cabang, Kepala Departemen, Senior Officer, Kepala Bidang, Kepala Bagian, Kepala Operasi Cabang, Officer --}}
          @elseif (($users->level >= 5) && ($users->level <= 11) && $users->cabang) <div class="mt-1 small text-muted text-end">{{ strtoupper($users->cabangTable['cabang']) }} | {{ strtoupper($users->bidangCabangTable['bidang']) }}
            </div>
            @elseif (($users->level >= 5) && ($users->level <= 11)) <div class="mt-1 small text-muted text-end">{{ strtoupper($users->satuanKerja['satuan_kerja']) }} | {{ strtoupper($users->departemenTable['departemen']) }}
      </div>


      @elseif ($users->level == 1)
      <div class="mt-1 small text-muted text-end">Admin</div>

      @elseif (($users->cabang) && ($users->levelTable['golongan'] <= 4)) <div class="mt-1 small text-muted text-end">{{ strtoupper($users->cabangTable['cabang']) }} | {{ strtoupper($users->bidangCabangTable['bidang']) }}
    </div>

    @else
    <div class="mt-1 small text-muted text-end">{{ strtoupper($users->satuanKerja->satuan_kerja) }} | {{ strtoupper($users->departemenTable->departemen) }}</div>

    @endif
  </div>

  {{-- Kepala Satuan Kerja, Kepala Divisi, Kepala Unit Kerja --}}
  @if (($users->level >= 2) && ($users->level <= 4)) <i class="fas fa-user-secret fa-2x"></i>

    {{-- Kepala Cabang, Kepala Departemen, Senior Officer, Kepala Bidang, Kepala Bagian, Kepala Operasi Cabang, Officer --}}
    @elseif (($users->level >= 5) && ($users->level <= 11)) <i class="fas fa-user-tie fa-2x"></i>

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
          <li class="nav-item">
            <a class="nav-link" href="/draft">
              <i class="fas fa-envelope"></i>
              <span class="nav-link-title ms-1">
                Draft
              </span>
            </a>
          </li>
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
                  {{-- Untuk Internal SKTILOG --}}
                  @if ($users->satuan_kerja == 2)
                  <a class="dropdown-item" href="/nomorSurat">
                    Registrasi Surat
                  </a>

                  {{-- Untuk golongan lebih dari 4 --}}
                  @if ($users->levelTable->golongan > 4)
                  <a class="dropdown-item" href="/otor">
                    Otorisasi Surat
                  </a>
                  @endif

                  @else
                  <a class="dropdown-item" href="/suratKeluar">
                    Surat Keluar
                  </a>

                  @if ($users->levelTable->golongan >4)
                  <a class="dropdown-item" href="/otor">
                    Otorisasi Surat
                  </a>
                  @endif
                  @endif
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
                Cetak Laporan
              </span>
            </a>
          </li>

          @if ($users->satuan_kerja == 2)
          <li class="nav-item">
            <a class="nav-link" href="/penomoran" target="_blank">
              <i class="fas fa-sort-numeric-down"></i>
              <span class="nav-link-title ms-1">
                Penomoran Surat
              </span>
            </a>
          </li>
          @endif

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
                  <a class="dropdown-item" href="/listUser">
                    Data Pengguna
                  </a>
                  <a class="dropdown-item" href="/registration">
                    Tambah Pengguna
                  </a>
                  <a class="dropdown-item" href="/satuanKerja">
                    Data Satuan Kerja/Divisi/Departemen Satu Tingkat di Bawah Direksi
                  </a>
                  <a class="dropdown-item" href="/departemen">
                    Data Departemen
                  </a>
                  <a class="dropdown-item" href="/kantorCabang">
                    Data Kantor Cabang
                  </a>
                  <a class="dropdown-item" href="/cabangPembantu">
                    Data Kantor Cabang Pembantu
                  </a>
                </div>
              </div>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#modalAktivitas">
              <i class="fas fa-skating"></i>
              <span class="nav-link-title ms-1">
                Aktivitas
              </span>
            </a>
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
              <a href="/logout" class="btn btn-danger w-100" style="text-decoration: none">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal cetak laporan --}}
<div class="modal fade" id="modalLaporan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cetak Laporan</h5>
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

@if ($users->level == 1)
{{-- Modal Aktivitas --}}
<div class="modal fade" id="modalAktivitas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Log Aktivitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/aktivitas" method="post">
        @csrf
        {{ method_field('POST') }}

        <div class="modal-body">
          {{-- Input user --}}
          <div class="col-sm-6 mb-3 form-user">
            <label for="user_id" class="form-label">User</label>
            <select class="form-select mb-3" aria-label=".form-select-sm example" name="user_id" id="user_id" style="width: 100%" required>
              <option value=""> ----- </option>
              <option value="all">Semua User</option>
              @foreach ($userLogs as $item)
              @if ($item->level == 1)
              @continue
              @endif
              <option value="{{ $item->id }}">{{ strtoupper($item->name) }} - {{ strtoupper($item->satuanKerja['inisial']) }} {{ strtoupper($item->departemenTable['inisial']) }}</option>
              @endforeach
            </select>
            <div class="col-sm-6 mb-3 form-user">
              <label for="tanggalmulai" class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai" required>
            </div>
            <div class="col-sm-6 mb-3 form-user">
              <label for="tanggalakhir" class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" id="tanggalakhir" name="tanggalakhir" required>
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
@endif