@extends('templates.index')

@section('content')
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session()->has('error'))
            <div class="alert alert-warning mt-3" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <form action="/satuanKerja" method="post">
                @csrf

                <div class="form-group mb-3">
                    <label for="satuan_kerja" class="form-label ">Satuan Kerja</label>
                    <input type="text" class="form-control" id="satuan_kerja" name="satuan_kerja">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

@endsection