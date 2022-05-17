@extends('templates.index')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if(session()->has('error'))
        <div class="alert alert-warning mt-3" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form action="/nomorSurat" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="created_by" class="form-label ">PIC</label>
                <input type="text" class="form-control" id="created_by" name="created_by" autocomplete="off" value="{{$users->name}}" readonly>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <input type="text" class="form-control" id="satuan_kerja_tujuan" name="satuan_kerja_tujuan" autocomplete="off" value="{{ $users->satuanKerja['satuan_kerja'] }}" readonly>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="departemen_asal" class="form-label">Department Asal</label>
                    <input type="text" class="form-control" id="departemen_asal" name="departemen_asal" autocomplete="off" value="{{ $users->departemenTable['departemen'] }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_tujuan" class="form-label">Satuan Kerja Tujuan</label>
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="satuan_kerja_tujuan" id="satuan_kerja_tujuan">
                        <option selected> ---- </option>
                        <option value="1">SKTI & LOG</option>
                        <option value="2">PPO</option>
                        <option value="3">PPO</option>
                        <option value="4">OPR</option>
                        <option value="5">PTI</option>

                    </select>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="departemen_tujuan" class="form-label">Department Tujuan</label>
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen_tujuan" id="departemen_tujuan">
                        <option selected> ---- </option>
                        <option value="1">Department 1</option>
                        <option value="2">Department 2</option>
                        <option value="3">Department 3</option>
                        <option value="4">Department 4</option>
                        <option value="5">Department 5</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" required id="perihal"></textarea>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran</label>
                <input class="form-control" type="file" id="lampiran" name="lampiran">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection