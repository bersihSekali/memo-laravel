@extends('templates.index')

@section('content')
    <!-- Page Heading -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Daftar Memo</h1>
        <!-- DataTales Example -->
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
                                <th scope="col">Lampiran</th>
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
                            
                            <tr>
                                <td class="align-top">{{$data['created_at']}}</td>
                                <td class="align-top">{{$data['nomor_surat']}}</td>
                                <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                                <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                                <td class="align-top">{{$data['perihal']}}</td>
                                <td class="align-top">{{$data['lampiran']}} </td>
                                @if($data['checker'])
                                <td class="align-top text-center">$data['checker']</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-outline-primary">+</button></td>
                                @endif
                                @if($data['tanggal_disposisi'])
                                <td class="align-top text-center">$data['tanggal_disposisi']</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-outline-primary">+</button></td>
                                @endif
                                @if($data['status'])
                                <td class="align-top text-center">Diselesaikan pada $data['tanggal_selesai']</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-sm btn-outline-primary">Selesaikan</button></td>
                                @endif
                                @if ($users['level'] == 'admin')
                                <td class="align-top">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modaledit-"><i class="fas fa-pen-square"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalhapus-"><i class="far fa-trash-alt"></i></button>
                                </td>
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