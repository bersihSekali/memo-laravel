@extends('templates.index')

@section('content')
    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        
        <a href="/nomorSurat/create" class="btn btn-info my-3">Tambah Surat</a>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">Lampiran</th>
                                <th scope="col">PIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                                <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                    <td class="align-top">{{$data['created_at']}}</td>
                                    <td class="align-top">{{$data['satuan_kerja_asal']}} {{$data['departemen_asal']}}</td>
                                    <td class="align-top">{{$data['satuan_kerja_tujuan']}} {{$data['departemen_tujuan']}}</td>
                                    <td class="align-top">{{$data['perihal']}}</td>
                                    <td class="align-top">{{$data['lampiran']}} </td>
                                    <td class="align-top">{{$data['created_by']}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Showing Detail Data -->
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
                        <pre>Satuan Kerja Asal    :{{ $data['satuan_kerja_asal'] }}</pre>
                        <pre>Department Asal      :{{ $data['departemen_asal'] }}</pre>
                        <pre>Satuan Kerja Tujuan  :{{ $data['satuan_kerja_tujuan'] }}</pre>
                        <pre>Department Tujuan    :{{ $data['departemen_tujuan'] }}</pre>
                        <pre>Perihal              :{{ $data['perihal'] }}</pre>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection