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
                <input type="hidden" class="form-control" id="id_telegram" name="id_telegram" value="{{ $users->id_telegram }}" readonly>
            </div>

            {{-- Input departemen asal / satuan kerja asal --}}
            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                </div>

                <div class="col-sm-6 mb-3" style="{{($users->levelTable->golongan == 7) ? 'display: none' : ''}}">
                    <label for="departemen_asal" class="form-label">Department Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_asal" id="departemen_asal">
                        <option value="{{ $users->departemen }}"> {{ $users->departemenTable['departemen'] }} </option>
                    </select>
                </div>
            </div>

            {{-- Tipe surat --}}
            <div class="form-group mb-3">
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="tipe_surat" value="1" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">Internal</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="tipe_surat" value="2" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">Eksternal</span>
                    </label>
                </div>
            </div>

            {{-- Input tujuan --}}
            <div class="form-group mb-3">
                <label for="tujuan" class="form-label formulir" style="display: none">Tujuan</label>
                {{-- Tujuan unit kerja --}}
                <div class="row justify-content-end tujuan-eksternal" style="display: none">
                    <div class="row">
                        <label for="">Unit Kerja</label>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col">
                            <select class="form-select" aria-label=".form-select-sm example" name="tujuan_unit_kerja[]" id="tujuan_unit_kerja" multiple="multiple">
                                <option id="unit_kerja" value="unit_kerja">Seluruh Unit Kerja</option>
                                @foreach ($satuanKerjas as $satuanKerja)
                                <option class="opsi_unit_kerja" value="{{ $satuanKerja->id }}">{{ $satuanKerja->satuan_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tujuan unit layanan --}}
                <div class="row justify-content-end tujuan-eksternal" style="display: none">
                    <div class="row mt-2">
                        <label for="">Unit Layanan</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" aria-label=".form-select-sm example" name="tujuan_kantor_cabang[]" id="tujuan_kantor_cabang" multiple="multiple">
                                <option id="kantor_cabang" value="kantor_cabang">SELURUH KANTOR CABANG</option>
                                @foreach ($cabangs as $cabang)
                                @if ($cabang->id == 1)
                                @continue
                                @endif
                                <option class="opsi_kantor_cabang_besar" value="S{{ $cabang->id }}">{{ $cabang->cabang }}</option>
                                @foreach ($bidangCabangs as $bidang)
                                @if ($bidang->cabang_id == $cabang->id)
                                <option class="opsi_kantor_bidang" value="{{ $bidang->id }}">- {{ $bidang->bidang }}</option>
                                @endif
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tujuan internal --}}
                <div class="row justify-content-end tujuan-internal" style="display: none">
                    <div class="row">
                        <label for="">Departemen Internal</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-select mb-3" aria-label=".form-select-sm example" name="tujuan_internal[]" id="tujuan_internal" multiple="multiple">
                                <option id="internal" value="internal">Seluruh Internal</option>
                                @foreach ($departemens as $departemen)
                                @if ($departemen->satuan_kerja == 1)
                                <option class="opsi_departemen" value="{{ $departemen->id }}">{{ $departemen->departemen }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
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
                <div class="col-sm-6 mb-3" name="pengganti_antar_satuan_kerja" id="pengganti_eksternal" style="display: none">
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

                <div class="col-sm-6 mb-3" name="pengganti_antar_departemen" id="pengganti_internal" style="display: none">
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
        $(".form-selectgroup-input").change(function() {
            var tipe = $(".form-selectgroup-input:checked").val();
            if (tipe == 1) {
                $('.formulir').show(1000).delay(100)
                $('.tujuan-eksternal').hide(500)
                $('.tujuan-internal').show(1000)
                $('#pengganti_eksternal').hide()
                $('#pengganti_internal').show()
            } else {
                $('.formulir').show(1000).delay(100)
                $('.tujuan-eksternal').show(1000)
                $('.tujuan-internal').hide(500)
                $('#pengganti_internal').hide()
                $('#pengganti_eksternal').show()
            }
        });

        $('#pejabat_pengganti').click(function() {
            $('#otor_pengganti').toggle();
        });

        $('#tujuan_satuan_kerja').select2({
            placeholder: "Select a state",
            allowClear: true
        })

        $('#tujuan_unit_kerja').change(function() {
            if ($('#unit_kerja').is(':selected')) {
                $('.opsi_unit_kerja').attr('disabled', 'disabled')
                $(".opsi_unit_kerja").prop("selected", false)
            } else {
                $('.opsi_unit_kerja').removeAttr('disabled')
            }
        });
        $('#tujuan_departemen_direksi').change(function() {
            if ($('#departemen_direksi').is(':selected')) {
                $('.opsi_departemen_direksi').attr('disabled', 'disabled')
                $(".opsi_departemen_direksi").prop("selected", false)
            } else {
                $('.opsi_departemen_direksi').removeAttr('disabled')
            }
        });
        $('#tujuan_kantor_cabang').change(function() {
            if ($('#kantor_cabang').is(':selected')) {
                $('.opsi_kantor_cabang_besar').attr('disabled', 'disabled')
                $('.opsi_kantor_cabang_besar').prop("selected", false)
                $('.opsi_kantor_bidang').attr('disabled', 'disabled')
                $('.opsi_kantor_bidang').prop("selected", false)
            } else {
                $('.opsi_kantor_cabang_besar').removeAttr('disabled')
                $('.opsi_kantor_bidang').removeAttr('disabled')
            }
        });
    });
</script>
@endsection