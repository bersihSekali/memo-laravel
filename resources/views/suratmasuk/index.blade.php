@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Memo Masuk</h1>
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
                            @if($users['level'] == 3)
                            <th scope="col">Disposisi</th>
                            @endif
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users['level'] == 2)
                        @foreach($datas as $data)
                        <tr class="{{ ($data['status'] == 3 ? 'table-bold' : 'table-light') }}" id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            @if($data['status'] == 4)
                            <td>Selesai pada {{date('Y-m-d', strtotime($data['tanggal_sk']))}}</td>
                            @else
                            <td>Belum Selesai</td>
                            @endif
                        </tr>
                        @endforeach
                        @else
                        @foreach($datas as $data)
                        <tr class="{{ ($data['status'] == 4 ? 'table-bold' : 'table-light') }}" id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data['pesan_disposisi']}}</td>
                            @if($data['tanggal_dep'])
                            <td>Selesai pada {{date('Y-m-d', strtotime($data['tanggal_dep']))}}</td>
                            @else
                            <td>Belum Selesai</td>
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

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
                            <td>: {{ strtoupper($data->created_by) }}</td>
                        </tr>

                        <tr>
                            <td>Tanggal Masuk</td>
                            <td>: {{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
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
            @if ($data['status'] == 3 && $users['level'] == 2)
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['id'] }}">Teruskan ke Kepala Departemen</button>
            </div>
            @elseif ($users['level'] == 3)
            <div class="modal-footer">
                <form action="/forward/{{$data['id']}}" method="post">
                    <button type="submit" class="btn btn-primary">Teruskan</button>
                </form>
                @if ($data['status'] == 4)
                <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['id'] }}">Selesaikan</button>
                @endif
            </div>
            @endif
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
                    @if ($users['level'] == 2)
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
                    @else
                    <p>Tekan tombol Selesaikan untuk mengakhiri.</p>
                    @endif
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