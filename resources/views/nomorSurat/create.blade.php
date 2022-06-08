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

            {{-- Input pembuat --}}
            <div class="form-group mb-3">
                <label for="created_by" class="form-label ">Pembuat</label>
                <input type="text" class="form-control" autocomplete="off" value="{{ strtoupper($users->name) }}" readonly>
                <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
            </div>

            {{-- Tipe surat --}}
            <div class="form-group mb-3">
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                      <input type="radio" name="tipe-surat" value="1" class="form-selectgroup-input">
                      <span class="form-selectgroup-label">Internal</span>
                    </label>
                    <label class="form-selectgroup-item">
                      <input type="radio" name="tipe-surat" value="2" class="form-selectgroup-input">
                      <span class="form-selectgroup-label">Eksternal</span>
                    </label>
                </div>
            </div>

            {{-- Input departemen / satuan kerja --}}
            <div class="form-group row formulir" style="display: none">
                <div class="col-sm-6 mb-3" id="eksternal">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                </div>

                <div class="col-sm-6 mb-3" id="internal">
                    <label for="departemen_asal" class="form-label">Department Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_asal" id="departemen_asal">
                        <option value="{{ $users->departemen }}"> {{ $users->departemenTable['departemen'] }} </option>
                    </select>
                </div>
            </div>

            {{-- Input tujuan --}}
            <div class="form-group mb-3 formulir" style="display: none">
                <label for="tujuan" class="form-label">Tujuan</label>
                <select class="form-select mb-3" aria-label=".form-select-sm example" name="tujuan" id="tujuan" multiple="multiple">
                    <option value=""> ---- </option>
                    @foreach ($satuanKerjas as $satuanKerja)
                        <option value="{{ $satuanKerja->id }}">{{ $satuanKerja->satuan_kerja }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Input perihal --}}
            <div class="form-group mb-3 formulir" style="display: none">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" required id="perihal" required></textarea>
            </div>

            {{-- Input pejabat pengganti --}}
            <div class="form-check formulir" style="display: none">
                <input class="form-check-input" type="checkbox" value="" id="pejabat_pengganti" name="pejabat_pengganti">
                <label class="form-check-label" for="pejabat_pengganti">
                    Pejabat Pengganti
                </label>
            </div>

            {{-- Input otor pengganti --}}
            <div class="form-group row" id="otor_pengganti" name="otor_pengganti" style="display: none">
                <div class="col-sm-6 mb-3" name="pengganti_antar_satuan_kerja" id="pengganti_antar_satuan_kerja" style="display: none;">
                    <label for="tunjuk_otor1_by" class="form-label">Otor 1 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor1_by">
                        <option value=""> ---- </option>
                        @foreach ($penggantis as $pengganti)
                            @if ($pengganti->levelTable->golongan == 7)
                                <option value="{{ $pengganti['id'] }}">{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->satuanKerja->satuan_kerja) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3" name="pengganti_antar_departemen" id="pengganti_antar_departemen" style="display: none;">
                    <label for="tunjuk_otor1_by" class="form-label">Otor 1 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor1_by">
                        <option value=""> ---- </option>
                        @foreach ($penggantis as $pengganti)
                            @if (($pengganti->levelTable->golongan == 6) || ($pengganti->levelTable->golongan == 5))
                                @if ($pengganti->satuan_kerja == 1)
                                    @if ($pengganti->level == 6)
                                        <option value="{{ $pengganti['id'] }}">{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->departemenTable->departemen) }}</option>
                                    @else
                                        <option value="{{ $pengganti['id'] }}">{{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->departemenTable->departemen) }}</option>
                                    @endif
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

            {{-- Input lampiran --}}
            <div class="mb-3 formulir" style="display: none">
                <label for="lampiran" class="form-label">Lampiran</label>
                <input class="form-control" type="file" id="lampiran" name="lampiran" required>
            </div>

            <button type="submit" class="btn btn-primary formulir" style="display: none">Simpan</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $(".form-selectgroup-input").change(function(){
            var tipe = $(".form-selectgroup-input:checked").val();
            if (tipe == 1) {
                $('.formulir').show(1000)
                $('#internal').show(1000)
                $('#eksternal').hide(500)
            } else {
                $('.formulir').show(1000)
                $('#internal').show(1000)
                $('#eksternal').show(500)
            }
            if (tipe == 1) {
                $('#pengganti_antar_departemen').show();
                $('#pengganti_antar_satuan_kerja').hide();
            } else {
                $('#pengganti_antar_satuan_kerja').show();
                $('#pengganti_antar_departemen').hide();
            }
        });
        
        $('#pejabat_pengganti').click(function() {
            $('#otor_pengganti').toggle();
            
        });

    });
</script>
@endsection