@extends('templates.index')

@section('content')
  <div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
      
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-body py-3">
        @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="table-responsive">
          <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                  <th class="fs-4" scope="col" width="10%">Tanggal</th>
                  <th class="fs-4" scope="col">Asal</th>
                  <th class="fs-4" scope="col">Tujuan</th>
                  <th class="fs-4" scope="col">Perihal</th>
                  <th class="fs-4" scope="col">PIC</th>
              </tr>
            </thead>

            <tbody>
              @foreach($datas as $data)
                {{-- Officer --}}
                @if ($users->level == 5)
                  @if (($data->status == 1) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{ $data['id'] }}" style="cursor: pointer;">
                      <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                      <td class="align-top">
                        @if ($data->departemen_asal == '')
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                        @else
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                        @endif
                      </td>
                      <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                      <td class="align-top">{{ $data->perihal }}</td>
                      <td class="align-top">{{ strtoupper($data->createdBy['name']) }} </td>
                    </tr>
                  @endif
                
                {{-- Kepala departemen / Senio Officer --}}
                @elseif (($users->level == 4) || ($users->level == 3))
                  {{-- Surat antar departemen --}}
                  @if (($data->status == 2) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{ $data['id'] }}" style="cursor: pointer;">
                      <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                      <td class="align-top">
                        @if ($data->departemen_asal == '')
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                        @else
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                        @endif
                      </td>
                      <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                      <td class="align-top">{{ $data->perihal }}</td>
                      <td class="align-top">{{ strtoupper($data->createdBy['name']) }} </td>
                    </tr>
                  
                  {{-- Surat antar satuan kerja   --}}
                  @elseif (($data->status == 1) && ($data->satuan_kerja_asal != $data->satuan_kerja_tujuan))
                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{ $data['id'] }}" style="cursor: pointer;">
                      <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                      <td class="align-top">
                        @if ($data->departemen_asal == '')
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                        @else
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                        @endif
                      </td>
                      <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                      <td class="align-top">{{ $data->perihal }}</td>
                      <td class="align-top">{{ strtoupper($data->createdBy['name']) }} </td>
                    </tr>
                  @endif
                
                {{-- Kepala satuan kerja  --}}
                @elseif ($users->level == 2)
                  @if (($data->status == 2) && ($data->satuan_kerja_asal != $data->satuan_kerja_tujuan))
                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{ $data['id'] }}" style="cursor: pointer;">
                      <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                      <td class="align-top">
                        @if ($data->departemen_asal == '')
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                        @else
                            {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                        @endif
                      </td>
                      <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                      <td class="align-top">{{ $data->perihal }}</td>
                      <td class="align-top">{{ strtoupper($data->createdBy['name']) }} </td>
                    </tr>
                  @endif
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal For Showing Detail Data -->
  @foreach($datas as $data)
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
                <td>Tanggal Registrasi</td>
                <td>: {{ $data->created_at }}</td>
              </tr>

              <tr>
                <td>Nomor Surat</td>
                <td>: 
                    @if (!$data->nomor_surat)
                        Setujui surat terlebih dahulu
                    @endif
                </td>
              </tr>

              <tr>
                <td>PIC</td>
                <td>: {{ strtoupper($data->createdBy->name) }}</td>
              </tr>
              
              <tr>
                <td>Asal</td>
                <td>: {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
              </tr>
              
              <tr>
                <td>Tujuan</td>
                <td>: {{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
              </tr>

              <tr>
                <td>Perihal</td>
                <td>: {{ $data->perihal }}</td>
              </tr>

              {{-- Kepala Departemen / Senior Officer --}}
              @if (($users->level == 4) && ($users->level == 3))
                {{-- Surat antar departemen  --}}
                @if (($data->status == 2) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
                  <td>Disetujui Oleh</td>
                  <td>: 
                    <span class="badge bg-success">
                      Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                    </span>
                    <span class="badge bg-secondary">
                      Pending Kadep
                    </span>
                  </td>
                @endif
              
              {{-- Kepala satuan kerja  --}}
              @elseif ($users->level == 2)
                {{-- Surat antar satuan kerja  --}}
                @if (($data->status == 2) && ($data->satuan_kerja_asal != $data->satuan_kerja_tujuan))
                <td>Disetujui Oleh</td>
                <td>: 
                  <span class="badge bg-success">
                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                  </span>
                  <span class="badge bg-secondary">
                    Pending Kadep
                  </span>
                </td>
                @endif
              @endif

              <tr>
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

  {{-- Modal approved for confirmation --}}
  <div class="modal modal-blur fade" id="modal-approved-{{ $data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-status bg-success"></div>

        <!-- Officer -->
        {{-- Otor2_by surat antar departemen --}}
        @if (($users->level == 5) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
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

        <!-- Kepala Departemen / Senior Officer -->
        @elseif (($users->level == 3) || ($users->level == 4))
          {{-- Otor1_by surat antar departemen --}}
          @if ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan)
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

          {{-- Otor2_by surat antar satuan Kerja --}}
          @else
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
        
        {{-- Kepala Satuan Kerja --}}
        @elseif (($users->level == 2) && ($data->satuan_kerja_asal != $data->satuan_kerja_tujuan))
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
  
  {{-- Modal rejected for confirmation --}}
  <div class="modal modal-blur fade" id="modal-rejected-{{ $data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-status bg-danger"></div>
        
        <!-- Officer -->
        @if (($users->level == 5) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan)) 
          {{-- Rejected Otor2_by surat antar departemen --}}
          <div class="modal-body text-center py-4">
            <h3>Apakah yakin ingin menolak?</h3>
            <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
            <form action="/otorisasi/{{ $data['id'] }}"  method="post" enctype="multipart/form-data">
              @csrf
              {{method_field('DELETE')}}
            
              <div class="mb-3">
                <input class="form-control" type="file" id="lampiran" name="lampiran" required>
              </div>
            
              <button type="submit" class="btn btn-danger w-100">Tolak</button>
            </form>
          </div>
        
        {{-- Kepala Departemen / Senior Officer --}}
        @elseif (($users->level == 4) || ($users->level == 3))
          {{-- Rejected Otor1_by surat antar departemen --}}
          @if ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan)
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menolak?</h3>
              <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span><br>
              <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
              <form action="/otorisasi/disApprovedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('POST')}}

                <div class="mb-3">
                  <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                </div>

                <button type="submit" class="btn btn-danger w-100">Tolak</button>
              </form>
            </div>

          {{-- Rejected Otor2_by surat antar departemen --}}
          @else
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menolak?</h3>
              <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span>
              <form action="/otorisasi/{{ $data['id'] }}"  method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('DELETE')}}

                <div class="mb-3">
                  <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                </div>

                <button type="submit" class="btn btn-danger w-100">Tolak</button>
            </form>
              </form>
            </div>
          @endif
        
        {{-- Rejected Otor1_by surat antar satuan kerja  --}}
        @elseif (($users->level == 2) && ($data->satuan_kerja_asal != $data->satuan_kerja_tujuan))
          <div class="modal-body text-center py-4">
            <h3>Apakah yakin ingin menolak?</h3>
            <span>Harap beri catatan dan unggah terlebih dahulu surat yang akan ditolak</span><br>
            <span class="badge bg-success mb-1">Note: {{ strtoupper($data->otor2By['name']) }} telah menyetujui surat ini</span>
            <form action="/otorisasi/disApprovedOtorSatu/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
              @csrf
              {{method_field('POST')}}

              <div class="mb-3">
                <input class="form-control" type="file" id="lampiran" name="lampiran" required>
              </div>

              <button type="submit" class="btn btn-danger w-100">Tolak</button>
          </form>
          </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
@endsection