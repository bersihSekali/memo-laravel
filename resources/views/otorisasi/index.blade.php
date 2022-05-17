@extends('templates.index')

@section('content')
    <div class="container">
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                @if(session()->has('success'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                 
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Asal</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">PIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                                @if (($data['otor_status'] == '1') && ($data['satuan_kerja_tujuan'] == $users['satuan_kerja']))    
                                    <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                                        <td class="align-top">{{$data['created_at']}}</td>
                                        <td class="align-top">{{ $data->satuanKerja['satuan_kerja'] }} {{ $data->departemen['departemen'] }}</td>
                                        <td class="align-top">{{$data['satuan_kerja_tujuan']}} {{$data['departemen_tujuan']}}</td>
                                        <td class="align-top">{{$data['perihal']}}</td>
                                        <td class="align-top">{{$data['created_by']}} </td>
                                    </tr>
                                @endif
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
@endsection