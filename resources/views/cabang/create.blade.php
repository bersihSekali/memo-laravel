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

            <form action="/departemen" method="post">
                @csrf

                <div class="form-group mb-3">
                    <label for="satuan_kerja" class="form-label ">Satuan Kerja</label>
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja" id="satuan_kerja">
                        <option selected> ---- </option>
                        @foreach($satuanKerja as $item)
                        <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="departemen" class="form-label ">Departemen</label>
                    <input type="text" class="form-control" id="departemen" name="departemen">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

@endsection