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
                                @if (($data['otor_status'] == '1') && ($data['satuan_kerja_asal'] == $users['satuan_kerja']))    
                                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                        <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                                        <td class="align-top">{{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                                        <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                                        <td class="align-top">{{$data['perihal']}}</td>
                                        <td class="align-top">{{$data['created_by']}} </td>
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
            
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menyetujui?</h3>
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
                    <form action="/otorisasi/{{ $data['id'] }}" method="post">
                        @csrf
                        {{method_field('PUT')}}
                        <button type="submit" class="btn btn-success w-100">Setujui</button>
                    </form>
                    </form>
                  </div>
                </div>
              </div>
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
            
            <div class="modal-body text-center py-4">
              <h3>Apakah yakin ingin menolak?</h3>
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
                    <form action="/otorisasi/{{ $data['id'] }}" method="post">
                        @csrf
                        {{method_field('DELETE')}}
                        <button type="submit" class="btn btn-danger w-100">Tolak</button>
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