@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Memo</h1>
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
                            <th scope="col" width="10%">Tanggal</th>
                            <th scope="col">No.</th>
                            <th scope="col">Asal</th>
                            <th scope="col">Perihal</th>
                            <th scope="col">Status</th>
                            @if($users['level'] == 'Admin')
                            <th scope="col">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            @if($data['status'] == 4)
                            <td class="align-top text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_selesai']))}}</td>
                            @else
                            <td>Belum Selesai</td>
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
                            <td>Disusun Oleh</td>
                            <td>: {{ strtoupper($data->Users['name']) }}</td>
                        </tr>

                        <tr>
                            <td>Tanggal Masuk</td>
                            <td>: {{date('Y-m-d', strtotime($data['tanggal_otor2']))}}</td>
                        </tr>

                        <tr>
                            <td>Nomor Surat</td>
                            <td>: {{ $data->nomor_surat }}</td>
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

                        @if($data['checker'])
                        <tr>
                            <td>Checker</td>
                            <td>: {{$data->checkerUser['name']}}</td>
                        </tr>
                        @endif

                        @if($data['pesan_disposisi'])
                        <tr>
                            <td>Disposisi</td>
                            <td>: {{$data['pesan_disposisi']}}</td>
                        </tr>
                        @endif

                        <tr>
                            <td>Lampiran</td>
                            <td>: <a href="/storage/{{ $data['lampiran'] }}"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>
            @if ($data['status'] != 4)
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['id'] }}">Teruskan ke Kepala Departemen</button>
            </div>
            @endif
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
            <form action="/checker/{{$data['id']}}" method="post">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="checker" id="checker">
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
            <form action="/suratMasuk/{{$data['id']}}" method="post">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="disposisiCheckbox">
                        <label class="form-check-label" for="flexCheckDefault">
                            Teruskan dengan disposisi
                        </label>
                    </div>
                    <div class="form-group mb-3" id="formPesan" style="display:none">
                        <label for="disposisi" class="form-label">Pesan Disposisi</label>
                        <input type="text" class="form-control" id="pesan_disposisi" name="pesan_disposisi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Selesaikan</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/disposisi/{{$data['id']}}" method="post" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="modal-body">
                    <div class="mb-3">
                        {{$data['perihal']}}
                    </div>
                    <div class="form-group mb-3">
                        <label for="keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tanggal_disposisi" name="tanggal_disposisi" value="{{date('Y-m-d')}}" readonly>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-sm-6">
                            <label for="satuan_kerja" class="form-label ">Satuan Kerja</label>
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja_tujuan_disposisi" id="satuan_kerja_tujuan_disposisi">
                                <option selected> ---- </option>
                                @foreach($satuanKerja as $item)
                                <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="departemen_tujuan_disposisi" class="form-label">Departemen Tujuan</label>
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen_tujuan_disposisi" id="departemen_tujuan_disposisi">
                                <option value=""> ---- </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="disposisi" class="form-label">Pesan Disposisi</label>
                        <input type="text" class="form-control" id="pesan_disposisi" name="pesan_disposisi">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lampiran" class="form-label">Lampiran</label>
                        <input class="form-control" type="file" id="lampiran_disposisi" name="lampiran_disposisi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="{{url('/assets/dist/js/jquery-3.6.0.min.js')}}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    jQuery(document).ready(function() {
        jQuery('#satuan_kerja_tujuan_disposisi').change(function() {
            var skid = jQuery(this).val();
            jQuery.ajax({
                url: '/getSatuanKerja',
                type: 'post',
                data: 'skid=' + skid + '&_token={{csrf_token()}}',
                success: function(result) {
                    jQuery('#departemen_tujuan_disposisi').html(result)
                }
            });
        });
        $('#disposisiCheckbox').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('#formPesan').show(12)
            } else {
                $('#formPesan').hide(12)
                // the checkbox is now no longer checked
            }
        });
    });
</script>
@endsection