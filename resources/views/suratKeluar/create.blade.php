@extends('templates.index')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if(session()->has('error'))
        <div class="alert alert-warning mt-3" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form action="/suratKeluar" method="post" enctype="multipart/form-data">
            @csrf

            {{-- Input pembuat --}}
            <div class="form-group mb-3">
                <label for="created_by" class="form-label ">Pembuat</label>
                <input type="text" class="form-control" autocomplete="off" value="{{ strtoupper($users->name) }}" readonly>
                <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
            </div>

            {{-- No Surat --}}
            <div class="form-group formulir mb-3" style="display: none;">
                <label for="nomor_surat" class="form-label ">Nomor Surat</label>
                <input type="text" class="form-control" autocomplete="off">
            </div>

            {{-- Input departemen / satuan kerja --}}
            <div class="form-group row formulir" style="display: none">
                <div class="col-sm-6 mb-3" id="eksternal">
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal" style="width: 100%;">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                </div>
            </div>

            {{-- Input tujuan --}}
            <div class="form-group mb-3 formulir" style="display: none">
                <label for="tujuan" class="form-label mb-3">Tujuan</label>
                <div class="row justify-content-end">
                    <div class="row mb-2">
                        <label for="">Unit Kerja</label>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col">
                            <select class="form-select mb-3" aria-label=".form-select-sm example" name="tujuan_unit_kerja[]" id="tujuan_unit_kerja" multiple="multiple" style="width: 100%;">
                                <option id="unit_kerja" value="unit_kerja">Seluruh Unit Kerja</option>
                                @foreach ($satuanKerjas as $satuanKerja)
                                <option class="opsi_unit_kerja" value="{{ $satuanKerja->id }}">{{ $satuanKerja->satuan_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="row my-2">
                        <label for="">Departemen Satu Tingkat di Bawah Direksi</label>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col">
                            <select class="form-select mb-3" aria-label=".form-select-sm example" name="tujuan_departemen_direksi[]" id="tujuan_departemen_direksi" multiple="multiple" style="width: 100%;">
                                <option id="departemen_direksi" value="departemen_direksi">Seluruh Departemen di Bawah Direksi</option>
                                @foreach ($departemenDireksis as $departemenDireksi)
                                <option class="opsi_departemen_direksi" value="{{ $departemenDireksi->id }}">{{ $departemenDireksi->departemen }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="row my-2">
                        <label for="">Unit Layanan</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-select mb-3" aria-label=".form-select-sm example" name="tujuan_kantor_cabang[]" id="tujuan_kantor_cabang" multiple="multiple" style="width: 100%;">
                                <option id="kantor_cabang" value="kantor_cabang">Seluruh Kantor Cabang</option>
                                @foreach ($kantorCabangs as $kantorCabang)
                                <option class="opsi_kantor_cabang" value="{{ $kantorCabang->id }}">{{ $kantorCabang->departemen }}</option>
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

                <div class="col-sm-6 mb-3">
                    <label for="tunjuk_otor2_by" class="form-label">Otor 2 Pengganti</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor2_by" id="tunjuk_otor2_by">
                        <option value="" selected> ---- </option>
                        @foreach ($penggantis as $pengganti)
                        @if (($pengganti->levelTable->golongan == 6) || ($pengganti->levelTable->golongan == 5))
                        @if ($pengganti->satuan_kerja == $users->satuan_kerja)
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
        $('.formulir').show(1000)
        $('#eksternal').show(500)
        $('#pengganti_antar_satuan_kerja').show();

        $('#pejabat_pengganti').click(function() {
            $('#otor_pengganti').toggle();
        });

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
                $('.opsi_kantor_cabang').attr('disabled', 'disabled')
                $('.opsi_kantor_cabang').prop("selected", false)
            } else {
                $('.opsi_kantor_cabang').removeAttr('disabled')
            }
        });
    });
</script>
@endsection