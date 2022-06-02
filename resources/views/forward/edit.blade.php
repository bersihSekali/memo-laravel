@extends('templates.index')

@section('content')
<!-- Page Heading -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Terusan Memo: {{$edits->perihal}}</h1>
    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <div class="table-responsive caption-top">
                <caption>Telah diteruskan kepada:</caption>
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <tbody>
                        @foreach($forwardeds as $item)
                        <tr>
                            <td>{{$item->users['name']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <form action="/forward/{{$edits['id']}}" method="post">
        @csrf
        {{method_field('PUT')}}
        <div class="form-group mb-3">
            <label for="satuan_kerja" class="form-label">Tambah</label>
            <select class="form-select form-select mb-3 forward-form" aria-label=".form-select-sm example" name="forward[]" id="forward-form" multiple="multiple">
                @foreach($forwards as $item)
                @if(!in_array($item['id'], $forwarded_ids))
                <option value="{{$item['id']}}">{{$item['name']}}</option>
                @endif
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
<!-- /.container-fluid -->




<script src="{{url('/assets/dist/js/jquery-3.6.0.min.js')}}" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection