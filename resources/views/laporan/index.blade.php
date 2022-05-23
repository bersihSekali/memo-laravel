@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <div class="table-responsive">
                <table id="tabel-laporan" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">No.</th>
                            <th scope="col">Asal</th>
                            <th scope="col">Tujuan</th>
                            <th scope="col">Perihal</th>
                            <th scope="col">Checker</th>
                            <th scope="col">Disposisi</th>
                            <th scope="col">Status</th>
                            @if($users['level'] == 'admin')
                            <th scope="col">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['created_at']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                            <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            @if($data['checker'])
                            <td class="align-top text-center">{{$data->checkerUser['name']}}</td>
                            @else
                            <td>-</td>
                            @endif
                            @if($data['tanggal_disposisi'])
                            <td class="align-top text-center">{{date("Y-m-d", strtotime($data['tanggal_disposisi']))}} <span type="button" data-bs-toggle="modal" data-bs-target="#disposisi-{{$data['id']}}" class="badge bg-info">Lihat Disposisi</span></td>
                            @else
                            <td>-</td>
                            @endif
                            @if($data['status'])
                            <td class="align-top text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_selesai']))}}</td>
                            @else
                            <td>Belum diselesaikan</td>
                            @endif
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