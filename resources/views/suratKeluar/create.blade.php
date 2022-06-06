@extends('templates.index')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('error'))
        <div class="alert alert-warning mt-3" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form action="/nomorSurat" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
            </div>

            <div class="form-group mb-3">
                <div class="col-sm-6">
                    <label for="nomorSurat" class="form-label">Nomor Surat</label>
                    <input type="text" class="form-control" id="nomorSurat">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal" style="width: 100%;">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                </div>
            </div>

            <div class="form-group row justify-content-between">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_tujuan" class="form-label">Satuan Kerja Tujuan</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_tujuan" id="satuan_kerja_tujuan" style="width: 100%;" required>
                        <option selected disabled> ---- </option>
                        @foreach ($satuanKerjas as $item)
                        @if($item['id'] != $users['satuan_kerja'])
                        <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="checkDepartemenTujuan">
                        <label for="departemen_tujuan" class="form-label">Departemen Tujuan (Tembusan)</label>
                    </div>
                    <div id="select-tembusan" style="display: none;">
                        <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_tujuan" id="departemen_tujuan">
                            <option value=""> ---- </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" required id="perihal" required></textarea>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran</label>
                @if ($users->level == 15)
                <input class="form-control" type="file" id="lampiran" name="lampiran" required>
                @else
                <input class="form-control" type="file" id="lampiran" name="lampiran">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    jQuery(document).ready(function() {
        jQuery('#satuan_kerja_tujuan').change(function() {
            var skid = jQuery(this).val();
            jQuery.ajax({
                url: '/getSatuanKerja',
                type: 'post',
                data: 'skid=' + skid + '&_token={{csrf_token()}}',
                success: function(result) {
                    jQuery('#departemen_tujuan').html(result)
                }
            });
        });
        $('#checkDepartemenTujuan').change(function() {
            if (this.checked) {
                $('#select-tembusan').show(12)
            } else {
                $('#select-tembusan').hide(12);
            }
        });
    });
</script>
@endsection