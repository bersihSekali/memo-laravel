@extends('templates.index')

@section('content')
  <div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-body py-3">
        @if(session()->has('success'))
        <div class="alert alert-success" role="alert" id="success-alert" style="display: none">
          {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
          <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="fs-4" scope="col" width="10%">Tanggal</th>
                <th class="fs-4" scope="col" width="20%">Asal</th>
                <th class="fs-4" scope="col">Perihal</th>
                <th class="fs-4" scope="col" width="10%">PIC</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($datas as $data)
              <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{ $data['id'] }}" style="cursor: pointer;">
                <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                <td class="align-top">
                  @if ($data->departemen_asal == '')
                  {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                  @else
                  {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                  @endif
                </td>
                <td class="align-top">{{ $data->perihal }}</td>
                <td class="align-top">{{ strtoupper($data->createdBy['name']) }} </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @foreach ($datas as $data)
    <div class="modal modal-blur fade" id="mail-{{$data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detil Surat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="table-responsive">
              <table id="tabel-data" style="width:100%">
                <tr>
                    <td width="20%">Tanggal Registrasi</td>
                    <td>: {{ $data->created_at }}</td>
                </tr>

                <tr>
                    <td width="20%">Nomor Surat</td>
                    <td>: 
                        @if (!$data->nomor_surat)
                            Setujui surat terlebih dahulu
                        @else
                            {{ $data->nomor_surat }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td width="20%">Pembuat</td>
                    <td>: {{ strtoupper($data->createdBy['name']) }}</td>
                </tr>
                
                <tr>
                    <td width="20%">Asal</td>
                    <td>: {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                </tr>
                
                <tr>
                    <td class="align-top" width="20%">Tujuan</td>
                    <td>
                        {{-- Tujuan kantor cabang --}}
                        @foreach ($tujuanCabangs as $item)
                            @if ($item->memo_id == $data->id)
                                @if (($item->all_flag == true) && ($item->bidang_id == null) && ($item->cabang_id ==1))
                                    : SELURUH KANTOR CABANG
                                @elseif ($item->cabang_id != null)
                                    : CABANG {{ $item->tujuanCabang->cabang }} <br>
                                @endif
                            @endif
                        @endforeach

                        {{-- Tujuan kantor bidang --}}
                        @foreach ($tujuanCabangs as $item)
                            @if ($item->memo_id == $data->id)
                                @if ($item->bidang_id != null)
                                    : {{ $item->tujuanBidang->bidang }} <br>
                                @endif
                            @endif
                        @endforeach

                        {{-- Tujuan departemen --}}
                        @foreach ($tujuanDepartemens as $item)
                            @if ($item->memo_id == $data->id)
                                : {{ $item->tujuanDepartemen->departemen }} <br>
                            @endif
                        @endforeach

                        {{-- Tujuan satuan kerja --}}
                        @foreach ($tujuanSatkers as $item)
                            @if ($item->memo_id == $data->id)
                                : {{ $item->tujuanSatuanKerja->satuan_kerja }} <br>
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    @if ($data->otor2_by_pengganti && $data->otor1_by_pengganti)
                      <td width="20%">Pejabat Pengganti</td>
                      <td>
                        : {{ strtoupper($data->otor2ByPengganti->name) }} sebagai otor 2 <br>
                        {{ strtoupper($data->otor1ByPengganti->name) }} sebagai otor 1
                      </td>
                    @elseif ($data->otor2_by_pengganti && !$data->otor1_by_pengganti)
                      <td width="20%">Pejabat Pengganti</td>
                      <td>
                        : {{ strtoupper($data->otor2ByPengganti->name) }} sebagai otor 2
                      </td>
                    @elseif (!$data->otor2_by_pengganti && $data->otor1_by_pengganti)
                      <td width="20%">Pejabat Pengganti</td>
                      <td>
                        : {{ strtoupper($data->otor1ByPengganti->name) }} sebagai otor 1
                      </td>
                    @endif
                  </tr>

                <tr>
                    <td class="align-top" width="20%">Perihal</td>
                    <td>: {{ $data->perihal }}</td>
                </tr>

                @if ($data->status == 0)
                    <tr>
                        <td width="20%">Catatan</td>
                        <td>: {{ $data->pesan_tolak }}</td>
                    </tr>
                @endif

                <tr>
                    <td width="20%">Status</td>
                    <td>
                        : @if ($data->status == 1)
                            <span class="badge bg-secondary">Pending</span>
                        
                        {{-- approved otor2_by --}}
                        @elseif ($data->status == 2)
                            {{-- Antar departemen --}}
                            @if ($data->internal == 1)
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-secondary">
                                    Pending Kadep
                                </span>

                            {{-- Antar satuan kerja --}}
                            @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-secondary">
                                    Pending Kasat
                                </span>
                            @endif
                        
                        {{-- approved otor1_by --}}
                        @elseif ($data->status == 3)
                            @if ($data->internal == 1)
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>

                            {{-- Antar satuan kerja --}}
                            @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>
                            @endif
                        
                        {{-- rejected --}}
                        @elseif ($data->status == 0)
                            {{-- rejected otor2_by --}}
                            @if ($data->otor1_by == 0)
                                <span class="badge bg-danger">
                                    Ditolak {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                            
                            {{-- rejected otor1_by --}}
                            @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-danger">
                                    Ditolak {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>
                            @endif
                        @endif
                    </td>
                </tr>

                <tr width="20%">
                    <td>Lampiran</td>
                    <td>: <a href="/storage/{{ $data['lampiran'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="modal-footer">
              <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-rejected-{{ $data['id'] }}">Tolak</button>

              <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-approved-{{ $data['id'] }}">Setuju</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal rejected for confirmation --}}
    <div class="modal modal-blur fade" id="modal-rejected-{{ $data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>

          {{-- Kepala bidang, kepala operasi cabang, kepala cabang pembantu, officer --}}
          @if ($users->levelTable->golongan == 5)
            {{-- Rejected Surat antar departemen sebagai otor2_by --}}
            @if (($data->status == 1) && ($data->internal == 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menolak?</h3>
                <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('DELETE')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran">
                  </div>

                  <div class="my-3">
                    <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                  </div>

                  <button type="submit" class="btn btn-danger w-100">Tolak</button>
                </form>
              </div>
            @endif

          {{-- Senior officer --}}
          @elseif (($users->levelTable->golongan == 6) && ($users->level == 7))
            {{-- Rejected Surat antar departemen sebagai otor2_by --}}
            @if (($data->status == 1) && ($data->internal == 1))
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menolak?</h3>
              <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
              <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('DELETE')}}

                <div class="mb-3">
                  <input class="form-control" type="file" id="lampiran" name="lampiran">
                </div>

                <div class="my-3">
                  <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-danger w-100">Tolak</button>
              </form>
            </div>

            {{-- Rejected surat antar departemen sebagai otor1_by --}}
            @elseif (($data->status == 2) && ($data->internal == 1) && ($data->otor2_by != $users->id))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menolak?</h3>
                <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span><br>
                <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
                <form action="/otorisasi/disApprovedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('POST')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran">
                  </div>

                  <div class="my-3">
                    <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                  </div>

                  <button type="submit" class="btn btn-danger w-100">Tolak</button>
                </form>
              </div>

            {{-- Surat antar satuan kerja sebagai otor2_by --}}
            @elseif (($data->status == 1) && ($data->internal != 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menolak?</h3>
                <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('DELETE')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran">
                  </div>

                  <div class="my-3">
                    <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                  </div>

                  <button type="submit" class="btn btn-danger w-100">Tolak</button>
                </form>
              </div>
            @endif
          
          {{-- Kepala departemen --}}
          @elseif (($users->levelTable->golongan == 6) && ($users->level == 6))
            {{-- Surat antar departemen sebagai otor1_by --}}
            @if (($data->status == 2) && ($data->internal == 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menolak?</h3>
                <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span><br>
                <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
                <form action="/otorisasi/disApprovedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('POST')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran">
                  </div>

                  <div class="my-3">
                    <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                  </div>

                  <button type="submit" class="btn btn-danger w-100">Tolak</button>
                </form>
              </div>

            {{-- Surat antar satuan kerja sebagai otor2_by --}}
            @elseif (($data->status == 1) && ($data->internal != 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menolak?</h3>
                <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('DELETE')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran">
                  </div>

                  <div class="my-3">
                    <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                  </div>

                  <button type="submit" class="btn btn-danger w-100">Tolak</button>
                </form>
              </div>
            @endif

          {{-- Kepala divisi, kepala satuan kerja, kepala unit kerja --}}
          @elseif ($users->levelTable->golongan == 7)
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menolak?</h3>
              <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span><br>
              <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
              <form action="/otorisasi/disApprovedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('POST')}}

                <div class="mb-3">
                  <input class="form-control" type="file" id="lampiran" name="lampiran">
                </div>

                <div class="my-3">
                  <input class="form-control" type="text" id="pesan_tolak" name="pesan_tolak" placeholder="Tambah catatan" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-danger w-100">Tolak</button>
              </form>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Modal approved for confirmation --}}
    <div class="modal modal-blur fade" id="modal-approved-{{ $data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-success"></div>

          {{-- Kepala bidang, kepala operasi cabang, kepala cabang pembantu, officer --}}
          @if ($users->levelTable->golongan == 5)
            {{-- Surat antar departemen sebagai otor2_by --}}
            @if (($data->status == 1) && ($data->internal == 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('PUT')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>
            
            {{-- Surat antar satuan kerja sebagai otor2_by --}}
            @elseif (($data->status == 1) && ($data->internal != 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('PUT')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>
            @endif
          
          {{-- Senior officer --}}
          @elseif (($users->levelTable->golongan == 6) && ($users->level == 7))
            {{-- Surat antar departemen sebagai otor2_by --}}
            @if (($data->status == 1) && ($data->internal == 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('PUT')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>
            
            {{-- Surat antar departemen sebagai otor1_by --}}
            @elseif (($data->status == 2) && ($data->internal == 1) && ($data->otor2_by != $users->id))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span><br>
                <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
                <form action="/otorisasi/approvedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('POST')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>

            {{-- Surat antar satuan kerja sebagai otor2_by --}}
            @elseif (($data->status == 1) && ($data->internal != 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('PUT')}}

                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>

                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>
            @endif

          {{-- Kepala departemen --}}
          @elseif (($users->levelTable->golongan == 6) && ($users->level == 6))
            {{-- Surat antar departemen sebagai otor1_by --}}
            @if (($data->status == 2) && ($data->internal == 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span><br>
                <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
                <form action="/otorisasi/approvedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('POST')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>

            {{-- Surat antar satuan kerja sebagai otor2_by --}}
            @elseif (($data->status == 1) && ($data->internal != 1))
              <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menyetujui?</h3>
                <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span>
                <form action="/otorisasi/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                  @csrf
                  {{method_field('PUT')}}
    
                  <div class="mb-3">
                    <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                  </div>
    
                  <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
              </div>
            @endif
          
          {{-- Kepala divisi, kepala satuan kerja, kepala unit kerja --}}
          @elseif ($users->levelTable->golongan == 7)
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menyetujui?</h3>
              <span>Harap tanda tangani dan cantumkan tanggal terlebih dahulu surat yang akan disetujui</span><br>
              <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
              <form action="/otorisasi/approvedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('POST')}}
    
                <div class="mb-3">
                  <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                </div>
    
                <button type="submit" class="btn btn-success w-100">Setujui</button>
              </form>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endforeach

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {
      $('#success-alert').show(1000).delay(1000);
      $('#success-alert').hide(1000);
    });
  </script>
@endsection