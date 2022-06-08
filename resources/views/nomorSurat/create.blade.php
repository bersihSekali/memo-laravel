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
                <input type="text" class="form-control" autocomplete="off" value="{{ strtoupper($users->name) }}" readonly>
                <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                </div>

                @if ($users->departemen != 0)
                <div class="col-sm-6 mb-3">
                    <label for="departemen_asal" class="form-label">Department Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_asal" id="departemen_asal">
                        <option value="{{ $users->departemen }}"> {{ $users->departemenTable['departemen'] }} </option>
                    </select>
                </div>
                @endif
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_tujuan" class="form-label">Satuan Kerja Tujuan</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_tujuan" id="satuan_kerja_tujuan" required>
                        <option selected disabled> ---- </option>
                        @foreach ($satuanKerjas as $item)
                        <option value="{{$item['id']}}">{{$item['satuan_kerja']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="departemen_tujuan" class="form-label">Department Tujuan</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_tujuan" id="departemen_tujuan" required>
                        <option value=""> ---- </option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" required id="perihal" required></textarea>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="pejabat_pengganti" name="pejabat_pengganti">
                <label class="form-check-label" for="pejabat_pengganti">
                    Pejabat Pengganti
                </label>
            </div>

            <div class="form-group row" id="otor_pengganti" name="otor_pengganti" style="display: none">
                <div class="col-sm-6 mb-3" name="pengganti_antar_satuan_kerja" id="pengganti_antar_satuan_kerja" style="display: none;">
                    <label for="tunjuk_otor1_by" class="form-label">Otor 1 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor1_by" id="tunjuk_otor1_by">
                        <option value=""> ---- </option>
                        @foreach ($penggantis as $pengganti)
                            @if ($pengganti->levelTable->golongan == 7)
                                <option value="{{$pengganti['id']}}">Kepala {{ $pengganti->satuanKerja->satuan_kerja }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3" name="pengganti_antar_departemen" id="pengganti_antar_departemen" style="display: none;">
                    <label for="tunjuk_otor1_by" class="form-label">Otor 1 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor1_by" id="tunjuk_otor1_by">
                        <option value=""> ---- </option>
                        @foreach ($penggantis as $pengganti)
                            @if (($pengganti->levelTable->golongan == 6) || ($pengganti->levelTable->golongan == 5))
                                @if ($pengganti->satuan_kerja == 1)
                                    <option value="{{$pengganti['id']}}">{{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->departemenTable->departemen) }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="tunjuk_otor2_by" class="form-label">Otor 2 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor2_by" id="tunjuk_otor2_by">
                        <option value="" selected> ---- </option>
                        @foreach ($penggantis as $pengganti)
                            @if (($pengganti->levelTable->golongan == 6) || ($pengganti->levelTable->golongan == 5))
                                @if ($pengganti->satuan_kerja == 1)
                                    @if ($pengganti->level == 6)
                                        <option value="{{ $pengganti['id']}}">{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->departemenTable->departemen) }}</option>
                                    @else
                                        <option value="{{ $pengganti['id']}}">{{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->departemenTable->departemen) }}</option>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran</label>
                <input class="form-control" type="file" id="lampiran" name="lampiran" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    jQuery(document).ready(function() {
        var skid
        jQuery('#satuan_kerja_tujuan').change(function() {
            skid = jQuery(this).val();
            jQuery.ajax({
                url: '/getSatuanKerja',
                type: 'post',
                data: 'skid=' + skid + '&_token={{csrf_token()}}',
                success: function(result) {
                    jQuery('#departemen_tujuan').html(result)
                }
            });
        });

        $('#pejabat_pengganti').click(function() {
            var sktid = $('#satuan_kerja_asal').val();
            $('#otor_pengganti').toggle();
            
            if (skid != sktid) {
                $('#pengganti_antar_satuan_kerja').show();
            } else {
                $('#pengganti_antar_departemen').show();
            }
        });
    });
</script>
@endsection