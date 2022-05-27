@extends('templates.index')

@section('content')
<div class="container">
    <div class="row g-2 align-items-center mb-2">
        <div class="col">
            <h2 class="page-title">
                Daftar Surat Terhapus
            </h2>
        </div>

        <div class="col-12 col-md-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="/nomorSurat/hapusPermanen" class="btn btn-danger d-none d-sm-inline-block">
                    <i class="fa-solid fa-dumpster-fire"></i>
                    Hapus Permanen
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
                <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="fs-4" scope="col" width="10%">Tanggal</th>
                            <th class="fs-4" scope="col">Asal</th>
                            <th class="fs-4" scope="col">Tujuan</th>
                            <th class="fs-4" scope="col">Perihal</th>
                            <th class="fs-4" scope="col">PIC</th>
                            <th class="fs-4" scope="col">Status</th>
                            <th class="fs-4" scope="col">Tanggal Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mails as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                            <td class="align-top">{{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                            <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data['created_by']}} </td>
                            <td class="align-top">
                                @if (($data->status == 2) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
                                    <span class="badge bg-success">
                                        Disetujui {{ strtoupper($data->otor2_by) }}
                                    </span>
                                @elseif (($data->status == 0) && ($data->otor1_by == ''))
                                    <span class="badge bg-warning">Ditolak {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                    </span>
                                @elseif ($data->status == 3)
                                    <span class="badge bg-success">
                                        Disetujui {{ strtoupper($data->otor1_by) }}
                                    </span>
                                @elseif (($data->status == 0) && ($data->otor1_by != ''))
                                    <span class="badge bg-warning">Ditolak {{ strtoupper($data->otor1_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td class="align-top">{{ date("Y-m-d", strtotime($data['deleted_at'])) }} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Modal For Showing Detail Data -->
    @foreach($mails as $data)
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
                                <td>Tanggal Hapus</td>
                                <td>: {{ $data->deleted_at }} by {{ strtoupper($data->deleted_by) }}</td>
                            </tr>

                            <tr>
                                <td>Nomor Surat</td>
                                <td>: 
                                    @if (!$data->nomor_surat)
                                        Setujui surat terlebih dahulu
                                    @else
                                        {{ $data->nomor_surat }}
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
                                <td>: {{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                            </tr>

                            <tr>
                                <td>Perihal</td>
                                <td>: {{ $data->perihal }}</td>
                            </tr>

                            <tr>
                                <td>Status</td>
                                <td>
                                    : @if ($data->status == 1)
                                        <span class="badge bg-secondary">Pending</span>
                                    
                                    {{-- approved otor2_by antar departemen --}}
                                    @elseif (($data->status == 2) && ($data->satuan_kerja_asal == $data->satuan_kerja_tujuan))
                                        <span class="badge bg-success">
                                            Disetujui {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                        </span>

                                    {{-- approved otor2_by antar satuan kerja --}}
                                    @elseif ($data->status == 2)
                                        <span class="badge bg-success">
                                            Disetujui {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                        </span>
                                        <span class="badge bg-secondary">
                                            Pending KaSat
                                        </span>
                                    
                                    {{-- Disapprove otor2_by --}}
                                    @elseif (($data->status == 0) && ($data->otor1_by == ''))
                                        <span class="badge bg-warning">
                                            Ditolak {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                        </span>
                                    
                                    {{-- approved otor2_by otor1_by --}}
                                    @elseif ($data->status == 3)
                                        <span class="badge bg-success">
                                            Disetujui {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                        </span> <br>

                                        <span class="badge bg-success">
                                            Disetujui {{ strtoupper($data->otor1_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                        </span>

                                    {{-- approved otor2_by disapproved otor1_by --}}
                                    @elseif (($data->status == 0) && ($data->otor1_by != ''))
                                        <span class="badge bg-success">
                                            Disetujui {{ strtoupper($data->otor2_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }} <br>
                                        </span> <br>
                                        <span class="badge bg-warning">Ditolak {{ strtoupper($data->otor1_by) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Lampiran</td>
                                <td>: <a href="/storage/{{ $data['lampiran'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach             
@endsection