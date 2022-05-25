@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">{{$title}}</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <div class="table-responsive">
                <table id="tabel-data" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal Disposisi</th>
                            <th scope="col">No.</th>
                            <th scope="col">Perihal</th>
                            <th scope="col">Pesan Disposisi</th>
                            <th scope="col">Checker</th>
                            <th scope="col">Status</th>
                            @if($users['level'] == 'Admin')
                            <th scope="col">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_disposisi']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data['pesan_disposisi']}}</td>
                            @if($data['checker_disposisi'])
                            <td class="align-top text-center">{{$data->checkerUserDisposisi['name']}}</td>
                            @elseif($users['level'] == 'Admin' | $data['status_disposisi']==1)
                            <td>-</td>
                            @else
                            <td class="align-top text-center"><button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalChecker-{{$data['id']}}">+</button></td>
                            @endif
                            @if($data['status_disposisi'])
                            <td class="align-top text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_selesai_disposisi']))}}</td>
                            @elseif($users['level'] == 'Admin')
                            <td>Belum diselesaikan</td>
                            @else
                            <td class="align-top text-center"><button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{$data['id']}}">Selesaikan</button></td>
                            @endif
                            @if($users['level'] == 'Admin')
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

@foreach($datas as $data)
<div class="modal modal-blur fade" id="disposisi-{{$data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detil Disposisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabel-data" style="width:100%">
                        <tr>
                            <td>Tanggal Disposisi</td>
                            <td>: {{date("Y-m-d", strtotime($data['tanggal_disposisi']))}}</td>
                        </tr>

                        <tr>
                            <td>Satuan Kerja Tujuan Disposisi</td>
                            <td>: {{$data->satuanKerjaDisposisi['satuan_kerja']}}
                            </td>
                        </tr>

                        <tr>
                            <td>Departemen Tujuan Disposisi</td>
                            <td>: {{$data->departemenDisposisi['departemen']}}
                            </td>
                        </tr>

                        <tr>
                            <td>Perihal</td>
                            <td>: {{ $data->pesan_disposisi }}</td>
                        </tr>

                        <tr>
                            <td>Lampiran</td>
                            <td>: <a href="/storage/{{ $data['lampiran_disposisi'] }}"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

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
                            <td>: {{ $data->nomor_surat }}
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
                            <td>: <a href="/storage/{{ $data['lampiran_disposisi'] }}"><button type="button" class="btn btn-info btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach ($datas as $data)
<div class="modal fade" id="modalChecker-{{ $data['id'] }}" tabindex="-1" aria-labelledby="modalCheckerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCheckerLabel">Tambah sebagai checker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/checkerDisposisi/{{$data['id']}}" method="post">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="checker_disposisi" id="checker_disposisi">
                            <option selected> ---- </option>
                            @foreach($checker as $item)
                            <option value="{{$item['id']}}">{{$item['name']}} ({{$item->satuanKerjaTable['satuan_kerja']}} - {{$item->departemenTable['departemen']}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach ($datas as $data)
<div class="modal fade" id="modalSelesai-{{ $data['id'] }}" tabindex="-1" aria-labelledby="modalSelesaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSelesaiLabel">Selesaikan Memo Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/disposisi/selesai/{{$data['id']}}" method="post">
                @csrf
                {{method_field('POST')}}
                <div class="modal-body">Klik tombol "Akhiri" di bawah untuk menyelesaikan.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection