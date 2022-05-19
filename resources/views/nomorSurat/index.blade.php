@extends('templates.index')

@section('content')
    <div class="container">
        <div class="row g-2 align-items-center mb-2">
            <div class="col">
              <h2 class="page-title">
                Daftar Semua Surat
              </h2>
            </div>

            <div class="col-12 col-md-auto ms-auto d-print-none">
              <div class="btn-list">
                <a href="/nomorSurat/create" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Surat
                </a>
              </div>
            </div>
          </div>
        
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                @if(session()->has('success'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                 
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">PIC</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                                @if (($data['satuan_kerja_asal'] == $users['satuan_kerja']))    
                                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                        <td class="align-top">{{$data['created_at']}}</td>
                                        <td class="align-top">{{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                                        <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                                        <td class="align-top">{{$data['perihal']}}</td>
                                        <td class="align-top">{{$data['created_by']}} </td>
                                        <td class="align-top">{{$data['otor_status']}} </td>
                                    </tr>
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
                        <table style="width:100%">
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
                                <td>: {{ strtoupper($data->created_by) }}</td>
                            </tr>
                            
                            <tr>
                                <td>Asal</td>
                                <td>: {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                            </tr>
                            
                            <tr>
                                <td>Tujuan</td>
                                <td>: {{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                            </tr>

                            <tr>
                                <td>Perihal</td>
                                <td>: {{ $data->perihal }}</td>
                            </tr>

                            <tr>
                                <td>Lampiran</td>
                                <td>: <a href="/storage/{{ $data['lampiran'] }}"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection