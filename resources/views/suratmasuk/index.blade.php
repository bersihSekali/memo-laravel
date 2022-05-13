@extends('templates.index')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Daftar Memo</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">No.</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">Lampiran</th>
                                <th scope="col">Checker</th>
                                <th scope="col">Disposisi</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            @if($data['nomor_surat'])
                            <tr>
                                <td class="align-top">{{$data['created_at']}}</td>
                                <td class="align-top">{{$data['nomor_surat']}}</td>
                                <td class="align-top">{{$data['satuan_kerja_asal']}} {{$data['departemen_asal']}}</td>
                                <td class="align-top">{{$data['satuan_kerja_tujuan']}} {{$data['departemen_tujuan']}}</td>
                                <td class="align-top">{{$data['perihal']}}</td>
                                <td class="align-top">{{$data['lampiran']}} </td>
                                <td class="align-top"><span class="badge badge-secondary">{{$data['perihal']}}</span></td>
                                <td class="align-top">
                                    <span class="badge badge-secondary">tidak ada</span>
                                </td>
                                <td class="align-top">
                                    <span class="badge badge-danger">belum selesai</span>
                                </td>
                                <td class="align-top">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modaledit-"><i class="fas fa-pen-square"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalhapus-"><i class="far fa-trash-alt"></i></button>
                                </td>
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