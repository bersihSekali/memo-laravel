@extends('templates.index')

@section('content')

  <div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

    <div class="card shadow mb-4">
      <div class="card-body py-3">
          <div class="table-responsive">
              <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">Tanggal</th>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">User</th>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">Aktifitas</th>
                          <th class="fs-4" scope="col" width="10%" style="text-align: center">Surat</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($datas as $data)
                      @if (($data['satuan_kerja_asal'] == $users['satuan_kerja']))
                      <tr id="data" data-bs-toggle="modal" data-bs-target="#log-{{$data['id']}}" style="cursor: pointer;">
                          <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                          <td class="align-top">{{ strtoupper($data->userID->name) }}</td>
                          <td class="align-top">{{ strtoupper($data->aktifitas) }} </td>
                          <td class="align-top">{{ $data->surat->perihal }}</td>
                      </tr>
                      @endif
                      @endforeach
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div>
  
  @foreach($datas as $data)
    <div class="modal modal-blur fade" id="log-{{$data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detil Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tabel-data" style="width:100%">
                            <tr>
                                <td width="20%">Tanggal Aktifitas</td>
                                <td>: {{ $data->created_at }}</td>
                            </tr>

                            <tr>
                                <td width="20%">User Aktifitas</td>
                                <td>: {{ strtoupper($data->userID->name) }}</td>
                            </tr>

                            <tr>
                                <td width="20%">Jenis Aktifitas</td>
                                <td>: {{ $data->aktifitas }}</td>
                            </tr>

                            <tr>
                                <td width="20%">Nomor Surat</td>
                                <td>: 
                                    @if (!$data->surat->nomor_surat)
                                        Setujui surat terlebih dahulu
                                    @else
                                        {{ $data->surat->nomor_surat }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td width="20%">Pembuat Surat</td>
                                <td>: {{ strtoupper($data->surat->createdBy['name']) }}</td>
                            </tr>
                            
                            <tr>
                                <td width="20%">Asal</td>
                                <td>: {{ $data->surat->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->surat->departemenAsal['departemen'] }}</td>
                            </tr>

                            <tr>
                                @if ($data->surat->otor2_by_pengganti && $data->surat->otor1_by_pengganti)
                                  <td width="20%">Pejabat Pengganti</td>
                                  <td>
                                    : {{ strtoupper($data->surat->otor2ByPengganti->name) }} sebagai otor 2 <br>
                                    {{ strtoupper($data->surat->otor1ByPengganti->name) }} sebagai otor 1
                                  </td>
                                @elseif ($data->surat->otor2_by_pengganti && !$data->surat->otor1_by_pengganti)
                                  <td width="20%">Pejabat Pengganti</td>
                                  <td>
                                    : {{ strtoupper($data->surat->otor2ByPengganti->name) }} sebagai otor 2
                                  </td>
                                @elseif (!$data->surat->otor2_by_pengganti && $data->surat->otor1_by_pengganti)
                                  <td width="20%">Pejabat Pengganti</td>
                                  <td>
                                    : {{ strtoupper($data->surat->otor1ByPengganti->name) }} sebagai otor 1
                                  </td>
                                @endif
                              </tr>

                            <tr>
                                <td class="align-top" width="20%">Perihal</td>
                                <td>: {{ $data->surat->perihal }}</td>
                            </tr>

                            @if ($data->surat->status == 0)
                                <tr>
                                    <td width="20%">Catatan</td>
                                    <td>: {{ $data->surat->pesan_tolak }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td width="20%">Status</td>
                                <td>
                                    : @if ($data->surat->status == 1)
                                        <span class="badge bg-secondary">Pending</span>
                                    
                                    {{-- approved otor2_by --}}
                                    @elseif ($data->surat->status == 2)
                                        {{-- Antar departemen --}}
                                        @if ($data->surat->internal == 1)
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                            <span class="badge bg-secondary">
                                                Pending Kadep
                                            </span>

                                        {{-- Antar satuan kerja --}}
                                        @else
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                            <span class="badge bg-secondary">
                                                Pending Kasat
                                            </span>
                                        @endif
                                    
                                    {{-- approved otor1_by --}}
                                    @elseif ($data->surat->status == 3)
                                        @if ($data->surat->internal == 1)
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor1)) }}
                                            </span>

                                        {{-- Antar satuan kerja --}}
                                        @else
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor1)) }}
                                            </span>
                                        @endif
                                    
                                    {{-- rejected --}}
                                    @elseif ($data->surat->status == 0)
                                        {{-- rejected otor2_by --}}
                                        @if ($data->surat->otor1_by == 0)
                                            <span class="badge bg-danger">
                                                Ditolak {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                        
                                        {{-- rejected otor1_by --}}
                                        @else
                                            <span class="badge bg-success">
                                                Disetujui {{ strtoupper($data->surat->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor2)) }}
                                            </span>
                                            <span class="badge bg-danger">
                                                Ditolak {{ strtoupper($data->surat->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->surat->tanggal_otor1)) }}
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            <tr width="20%">
                                <td>Lampiran</td>
                                <td>: <a href="/storage/{{ $data->surat['lampiran'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal delete for confirmation --}}
    <div class="modal modal-blur fade" id="modal-delete-{{ $data['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
      
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menghapus surat?</h3>
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
                    <form action="/nomorSurat/{{ $data['id'] }}"  method="post">
                        @csrf
                        {{method_field('DELETE')}}
                    
                        <button type="submit" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$data['id']}}">Hapus</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  @endforeach   
@endsection