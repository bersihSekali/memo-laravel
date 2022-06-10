@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mb-3">
        <h1 class="h3 mb-2 text-gray-800">Memo:</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col" width="10%">Tanggal</th>
                        <th scope="col">No.</th>
                        <th scope="col">Asal</th>
                        <th scope="col">Perihal</th>
                        @if($users->levelTable['golongan'] <= 6) <th scope="col">Disposisi</th>
                            @endif
                    </tr>
                </thead>
                <tbody>
                    <tr class="{{ ($edits['status'] == 3 ? 'table-bold' : 'table-light') }}" id="data">
                        <td class="align-top">{{date('Y-m-d', strtotime($edits['tanggal_otor1']))}}</td>
                        <td class="align-top">{{$edits->nomor_surat}}</td>
                        <td class="align-top">{{$edits->satuanKerjaAsal['satuan_kerja']}}</td>
                        <td class="align-top">{{$edits['perihal']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <form action="/tujuanDepartemen/{{$edits['id']}}" method="post">
            @csrf
            {{method_field('PUT')}}
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="departemen_tujuan" class="form-label ">Teruskan ke:</label>
                    <select class="form-select on-modal mb-3" aria-label=".form-select-sm example" name="departemen_tujuan[]" id="departemen_tujuan" multiple="multiple">
                        @foreach($departemens as $item)
                        @if($item->satuan_kerja == $users->satuan_kerja)
                        <option value="{{$item['id']}}">{{$item['departemen']}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3" id="formPesan">
                    <label for="disposisi" class="form-label">Pesan Disposisi</label>
                    <input type="text" class="form-control" id="pesan_disposisi" name="pesan_disposisi">
                </div>
                <p class="badge bg-warning">Pesan akan ditandai sebagai telah dibaca.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Selesaikan</button>
            </div>
        </form>
    </div>
</div>
<!-- /.container-fluid -->




<script src="{{url('/assets/dist/js/jquery-3.6.0.min.js')}}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection