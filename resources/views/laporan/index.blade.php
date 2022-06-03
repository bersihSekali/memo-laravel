@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h2 mb-2 text-gray-800" id="judul">Pencatatan Memo {{ucwords($requests['jenis'])}}</h1>
    <h1 class="h4 mb-2 text-gray-800" id="tanggal">{{dateWithFormatter($requests['tanggalmulai'])}} s.d. {{dateWithFormatter($requests['tanggalakhir'])}}</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <div class="table-responsive caption-top">
                <table id="tabel-laporan" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal Surat</th>
                            @if ($requests['jenis'] == 'masuk')
                            <th scope="col">Tanggal Masuk</th>
                            @elseif ($requests['jenis'] == 'keluar')
                            <th scope="col">Tanggal Keluar</th>
                            @endif
                            <th scope="col">Disusun Oleh</th>
                            <th scope="col">Nomor Surat</th>
                            <th scope="col">Perihal</th>
                            <th scope="col">Asal</th>
                            <th scope="col">Tujuan</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['created_at']))}}</td>
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                            <td class="align-top">{{$data['created_by']}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                            <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                            @if($data['tanggal_sk'])
                            <td class="align-top text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_sk']))}}</td>
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