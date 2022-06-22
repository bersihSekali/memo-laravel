@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mb-3">
        @if(session()->has('success'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('success') }}
        </div>
        @endif
        <h1 class="h3 mb-2 text-gray-800">Memo:</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col" width="10%">Tanggal</th>
                        <th scope="col">No.</th>
                        <th scope="col">Asal</th>
                        <th scope="col">Perihal</th>
                        @if($users->levelTable['golongan'] <= 6) <th scope="col">Pesan</th>
                            @endif
                    </tr>
                </thead>
                <tbody>
                    <tr class="{{ ($edits['status'] == 3 ? 'table-bold' : 'table-light') }}" id="data">
                        <td class="align-top">{{date('Y-m-d', strtotime($edits['tanggal_otor1']))}}</td>
                        <td class="align-top">{{$edits->nomor_surat}}</td>
                        <td class="align-top">
                            @if ($edits->departemen_asal)
                            {{$edits->satuanKerjaAsal['satuan_kerja']}} | {{$edits->departemenAsal['departemen']}}
                            @elseif ($edits->cabang_asal)
                            {{$edits->cabangAsal['cabang']}}
                            @else
                            {{$edits->satuanKerjaAsal['satuan_kerja']}}
                            @endif
                        </td>
                        <td class="align-top">{{$edits['perihal']}}</td>
                        <td class="align-top">{{$edits->forwardCabang['pesan_disposisi']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-3">
        <h1 class="h3 mb-2 text-gray-800">Telah diteruskan kepada:</h1>
        <div class="table-responsive caption-top">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forwardeds as $item)
                    <tr>
                        <td>{{$item->users['name']}}</td>
                        <td>{{$item->pesan_disposisi}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <h1 class="h3 mb-2 text-gray-800">Tambahkan Tujuan:</h1>
        <form action="/forwardCabang/{{$edits['id']}}" method="post">
            @csrf
            {{method_field('PUT')}}
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="user_tujuan" class="form-label ">Teruskan ke:</label>
                    <select class="form-select on-modal mb-3" aria-label=".form-select-sm example" name="user_tujuan[]" id="user_tujuan" multiple="multiple">
                        @foreach($forwards as $item)
                        @if(!(in_array($item['id'], $forwarded_ids) || $item['id'] == $users['id']))
                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3" id="formPesan">
                    <label for="disposisi" class="form-label">Pesan Disposisi</label>
                    <input type="text" class="form-control" id="pesan_disposisi" name="pesan_disposisi">
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" href="{{route('suratMasuk')}}">Kembali ke Surat Masuk</a>
                <button class="btn btn-primary" type="submit">Selesaikan</button>
            </div>
        </form>
    </div>
</div>
<!-- /.container-fluid -->




<script src="{{url('/assets/dist/js/jquery-3.6.0.min.js')}}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection