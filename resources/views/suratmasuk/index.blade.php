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
                                @if($users['level'] == 'admin')
                                <th scope="col">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                <td class="align-top">{{$data['created_at']}}</td>
                                <td class="align-top">{{$data['nomor_surat']}}</td>
                                <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                                <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                                <td class="align-top">{{$data['perihal']}}</td>
                                <td class="align-top">{{$data['lampiran']}} </td>
                                @if($data['checker'])
                                <td class="align-top text-center">$data['checker']</td>
                                @elseif($users['level'] == 'admin')
                                <td>-</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalChecker-{{$data['id']}}">+</button></td>
                                @endif
                                @if($data['tanggal_disposisi'])
                                <td class="align-top text-center">$data['tanggal_disposisi']</td>
                                @elseif($users['level'] == 'admin')
                                <td>-</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDisposisi-{{$data['id']}}">+</button></td>
                                @endif
                                @if($data['status'])
                                <td class="align-top text-center">Diselesaikan pada $data['tanggal_selesai']</td>
                                @elseif($users['level'] == 'admin')
                                <td>Belum diselesaikan</td>
                                @else
                                <td class="align-top text-center"><button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{$data['id']}}">Selesaikan</button></td>
                                @endif
                                @if($users['level'] == 'admin')
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
<div class="modal fade" id="mail-{{ $data['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detil Surat</h5>
            </div>

            <div class="modal-body">
                <pre>Tanggal              :{{ $data['created_at'] }}</pre>
                <pre>Nomor Surat          :{{ $data['nomor_surat'] }}</pre>
                <pre>PIC                  :{{ $data['created_by'] }}</pre>
                <pre>Satuan Kerja Asal    :{{ $data->satuanKerjaAsal['satuan_kerja'] }}</pre>
                <pre>Department Asal      :{{ $data->departemenAsal['departemen'] }}</pre>
                <pre>Satuan Kerja Tujuan  :{{ $data->satuanKerjaTujuan['satuan_kerja'] }}</pre>
                <pre>Department Tujuan    :{{ $data->departemenTujuan['departemen'] }}</pre>
                <pre>Perihal              :{{ $data['perihal'] }}</pre>
                <pre>Lampiran             :<a href="/storage/{{ $data['lampiran'] }}"><button type="button" class="btn btn-info">Lihat Lampiran</button></a></pre>
            </div>

            <div class="modal-footer">
                <form action="/otorisasi/{{ $data['id'] }}" method="post">
                    @csrf
                    {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>

                <form action="/otorisasi/{{ $data['id'] }}" method="post">
                    @csrf
                    {{method_field('PUT')}}
                    <button type="submit" class="btn btn-primary">Setujui</button>
                </form>
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/checker" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja" id="satuan_kerja">
                            <option selected> ---- </option>
                            @foreach($checker as $item)
                            <option value="{{$item['id']}}">{{$item['name']}} ({{$item->satuanKerjaTable['satuan_kerja']}} - {{$item->departemenTable['departemen']}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="">Simpan</a>
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/selesai" method="post">
                @csrf
                <div class="modal-body">Klik tombol "Akhiri" di bawah untuk mengakhiri pemeriksaan.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="">Selesaikan</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach ($datas as $data)
<div class="modal fade" id="modalDisposisi-{{ $data['id'] }}" tabindex="-1" aria-labelledby="modalDisposisiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDisposisiLabel">Tambah Disposisi</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/disposisi" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="keluar" name="keluar">
                    </div>
                    <div class="form-group">
                        <label for="tujuan" class="form-label">Tujuan Disposisi</label>
                        <input type="text" class="form-control" id="tujuan" name="tujuan">
                    </div>
                    <div class="form-group">
                        <label for="disposisi" class="form-label">Pesan Disposisi</label>
                        <input type="text" class="form-control" id="disposisi" name="disposisi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Lampiran</label>
                        <div class="custom-file">
                            <label class="custom-file-label" for="customFile" id="lampirankeluar-label">Choose file</label>
                            <input type="file" class="custom-file-input" id="lampirankeluar" name="lampirankeluar" onchange="previewkeluar()">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection