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
                            @if($users->levelTable['golongan'] == 7)
                            <th scope="col" width="10%">Tanggal Surat</th>
                            @elseif($users->levelTable['golongan'] <= 6) <th scope="col" width="10%">Tanggal Masuk</th>
                                @endif
                                <th scope="col">No.</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Perihal</th>
                                @if($users->levelTable['golongan'] <= 6) <th scope="col">Pesan Disposisi</th>
                                    @endif
                                    <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->levelTable['golongan'] == 7)
                        @foreach($datas as $data)
                        <tr class="{{ ($data['status_baca'] == 1 ? 'table-light' : 'table-bold') }}" id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['memo_id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">
                                @if ($data->departemen_asal)
                                {{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}
                                @else
                                {{$data->satuanKerjaAsal['satuan_kerja']}}
                                @endif
                            </td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            @if($data['status_baca'] == 1)
                            <td><span class="badge bg-success me-1"></span>Selesai</td>
                            @else
                            <td><span class="badge bg-warning me-1"></span>Belum Selesai</td>
                            @endif
                        </tr>
                        @endforeach
                        @elseif($users->levelTable['golongan'] == 6)
                        @foreach($datas as $data)
                        <tr class="{{ ($data['status_baca'] == 1 ? 'table-light' : 'table-bold') }}" id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['memo_id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_disposisi']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">
                                @if ($data->departemen_asal)
                                {{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}
                                @else
                                {{$data->satuanKerjaAsal['satuan_kerja']}}
                                @endif
                            </td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data['pesan_disposisi']}}</td>
                            @if($data['status_baca'] == 1)
                            <td><span class="badge bg-success me-1"></span>Selesai</td>
                            @else
                            <td><span class="badge bg-warning me-1"></span>Belum Selesai</td>
                            @endif
                        </tr>
                        @endforeach
                        @else
                        @foreach($datas as $data)
                        <tr class="{{ ($data['status_baca'] == 1 ? 'table-light' : 'table-bold') }}" id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['memo_id']}}" style="cursor: pointer;">
                            <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_disposisi']))}}</td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">
                                @if ($data->departemen_asal)
                                {{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}
                                @else
                                {{$data->satuanKerjaAsal['satuan_kerja']}}
                                @endif
                            </td>
                            <td class="align-top">{{$data['perihal']}}</td>
                            <td class="align-top">{{$data['pesan_disposisi']}}</td>
                            @if($data['status_baca'] == 1)
                            <td><span class="badge bg-success me-1"></span>Selesai</td>
                            @else
                            <td><span class="badge bg-warning me-1"></span>Belum Selesai</td>
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
<div class="modal modal-blur fade" id="mail-{{$data['memo_id']}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <td width="20%">Tanggal Registrasi</td>
                            <td>: {{ $data->created_at }}</td>
                        </tr>

                        <tr>
                            <td>Disusun Oleh</td>
                            <td>: {{ucwords($data->createdBy['name'])}}</td>
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

                        <tr>
                            <td>Dibaca pada</td>
                            <td>: {{date('Y-m-d', strtotime($data['tanggal_baca']))}}</td>
                        </tr>

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
            @if ($users->levelTable['golongan'] == 7)
            @if (!$data->status_baca)
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['memo_id'] }}">Selesaikan</button>
            </div>
            @else
            <div class="modal-footer">
                <a class="btn btn-secondary" href="/tujuanDepartemen/{{$data['memo_id']}}/edit">Disposisi</a>
            </div>
            @endif
            @elseif ($users->levelTable['golongan'] >= 6)
            @if (!$data->status_baca)
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['memo_id'] }}">Selesaikan</button>
            </div>
            @else
            <div class="modal-footer">
                <a class="btn btn-secondary" href="/forward/{{$data['memo_id']}}/edit">Terusan</a>
            </div>
            @endif
            @elseif ($users->levelTable['golongan'] < 6) <div class="modal-footer">
                @if (!$data->status_baca)
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelesai-{{ $data['memo_id'] }}">Selesaikan</button>
                </div>
                @endif
        </div>
        @endif
    </div>
</div>
</div>
@endforeach

@foreach ($datas as $data)
<div class="modal fade" id="modalSelesai-{{ $data['memo_id'] }}" tabindex="-1" aria-labelledby="modalSelesaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSelesaiLabel">Selesaikan Memo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if ($users->levelTable['golongan'] == 7)
            <form action="/tujuanDepartemen/selesaikan/{{$data['memo_id']}}" method="post">
                @csrf
                {{method_field('POST')}}
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <p>Apakah anda yakin ingin menandai pesan sebagai telah dibaca?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Yakin</button>
                </div>
            </form>
            @elseif ($users->levelTable['golongan'] == 6)
            <form action="/forward/selesaikan/{{$data['memo_id']}}" method="post">
                @csrf
                {{method_field('POST')}}
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <p>Apakah anda yakin ingin menandai pesan sebagai telah dibaca?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Yakin</button>
                </div>
            </form>
            @elseif ($users->levelTable['golongan'] < 6) <form action="/forward/baca/{{$data['memo_id']}}" method="post">
                @csrf
                {{method_field('POST')}}
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <p>Apakah anda yakin ingin menandai pesan sebagai telah dibaca?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Yakin</button>
                </div>
                </form>
                @endif
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
    });
</script>
@endsection