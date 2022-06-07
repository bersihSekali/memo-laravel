@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-2">
            <div class="col">
                <h2 class="page-title">
                    Surat Keluar
                </h2>
            </div>
            @if ($users->satuan_kerja != 1 | $users->levelTable['golongan'] == 6)
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/suratKeluar/create" class="btn btn-primary d-none d-sm-inline-block">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Surat
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="card shadow mb-4">
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table id="tabel-data" class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="fs-4" scope="col" width="10%">Tanggal</th>
                                <th class="fs-4" scope="col">Asal</th>
                                <th class="fs-4" scope="col">Tujuan</th>
                                <th class="fs-4" scope="col">Perihal</th>
                                <th class="fs-4" scope="col">PIC</th>
                                <th class="fs-4" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mails as $data)
                            <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                                <td class="align-top">
                                    @if ($data->departemen_asal == '')
                                    {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                                    @else
                                    {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($data->departemen_tujuan == '')
                                    {{ $data->satuanKerjaTujuan['satuan_kerja'] }}
                                    @else
                                    {{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}
                                    @endif
                                <td class="align-top">{{ $data->perihal }}</td>
                                <td class="align-top">{{ strtoupper($data->createdBy['name'] )}} </td>
                                <td class="align-top">
                                    {{-- Setuju --}}
                                    @if ($data->status == 3)
                                    <span class="badge bg-success">Disetujui</span>

                                    {{-- Ditolak antar departemen --}}
                                    @elseif ($data->status == 0)
                                    <span class="badge bg-warning">Ditolak</span>

                                    {{-- Pending --}}
                                    @else
                                    <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection