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
                    <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">No.</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Perihal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            @if (($data->satuanKerjaAsal['satuan_kerja'] == $users->satuanKerja['satuan_kerja']) && ($data->nomor_surat != ''))
                            <tr>
                                <td class="align-top">{{$data['created_at']}}</td>
                                <td class="align-top">{{$data['nomor_surat']}}</td>
                                <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                                <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                                <td class="align-top">{{$data['perihal']}}</td>
                            </tr>
                            @endif
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