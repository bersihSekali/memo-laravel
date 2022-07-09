@extends('templates.index')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<link href="{{url('assets/vendor/summernote/summernote-bs4.css')}}" rel="stylesheet" />
<script src="{{url('assets/vendor/summernote/summernote-bs4.js')}}"></script>

<div class="row justify-content-center">
    <div class="col-md-10">
        @if(session()->has('error'))
        <div class="alert alert-warning mt-3" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['inisial'] }}
                        </option>
                    </select>
                </div>

                <div class="col-sm-6 mb-3" style="{{($users->levelTable->golongan == 7) ? 'display: none' : ''}}">
                    <label for="departemen_asal" class="form-label">Department Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="departemen_asal" id="departemen_asal">
                        <option value="{{ $users->departemen }}"> {{ $users->departemenTable['inisial'] }} </option>
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

            {{-- Kriteria --}}
            <div class="form-group formulir mb-3" style="display: none;">
                <div class="col-md-6">
                    <label for="kriteria" class="form-label ">Kriteria Informasi</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="kriteria" style="width: 100%;">
                        <option selected disabled> -- Pilih salah satu -- </option>
                        <option value="INTERNAL BCA SYARIAH"> INTERNAL BCA SYARIAH </option>
                        <option value="RAHASIA"> RAHASIA </option>
                        <option value="SANGAT RAHASIA"> SANGAT RAHASIA </option>
                    </select>
                </div>
            </div>

            {{-- No Surat --}}
            <div class="form-group formulir mb-3" style="display: none;">
                <div class="col-md-6">
                    <label for="nomor_surat" class="form-label ">Nomor Surat</label>
                    <input type="text" class="form-control" autocomplete="off" name="nomor_surat" value="{{old('nomor_surat')}}">
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
                                <option class="opsi_unit_kerja" value="{{ $satuanKerja->id }}">{{ $satuanKerja->inisial
                                        }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tujuan unit layanan --}}
                <div class="row justify-content-end tujuan-eksternal" style="display: none">
                    <div class="row mt-2">
                        <label for="">Kantor Cabang / Unit Layanan</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" aria-label=".form-select-sm example" name="tujuan_kantor_cabang[]" id="tujuan_kantor_cabang" multiple="multiple">
                                <option id="kantor_cabang" value="kantor_cabang">SELURUH KANTOR LAYANAN</option>
                                @foreach ($cabangs as $cabang)
                                <option class="opsi_kantor_cabang_besar besar-{{ $cabang->id }}" value="{{ $cabang->id }}">
                                    {{ $cabang->cabang }}
                                </option>
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
                            <select class="form-select kelas mb-3" aria-label=".form-select-sm example" name="tujuan_internal[]" id="tujuan_internal" multiple="multiple">
                                <option id="internal" value="internal">Seluruh Internal</option>
                                @foreach ($departemens as $departemen)
                                <option id="{{ $departemen->id }}" class="opsi_departemen" value="{{ $departemen->id }}">{{ $departemen->inisial }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input perihal --}}
            <div class="form-group mb-3 formulir" style="display: none">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" required id="perihal" required>{{old('perihal')}}</textarea>
            </div>

            {{-- Input otor pengganti --}}
            <div class="form-group row formulir" id="otor_pengganti" name="otor_pengganti" style="display: none">
                <div class="col-sm-6 mb-3" name="pengganti1_antar_satuan_kerja" id="pengganti1_eksternal" style="display: none">
                    <label for="tunjuk_otor1_by" class="form-label">Tanda Tangan 1</label>
                    <select class="form-select mb-3 otor-pengganti" aria-label=".form-select-sm example" name="tunjuk_otor1_by" id="tunjuk_otor1_by_eksternal">
                        <option selected disabled> -- Pilih salah satu -- </option> @foreach ($penggantis as $pengganti)
                        @if ($pengganti->levelTable->golongan == 7)
                        <option value="{{ $pengganti['id'] }}">
                            {{ strtoupper($pengganti->name) }} - KA. {{strtoupper($pengganti->satuanKerja->inisial) }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3" name="pengganti1_antar_departemen" id="pengganti1_internal" style="display: none">
                    <label for="tunjuk_otor1_by" class="form-label">Tanda Tangan 1</label>
                    <select class="form-select mb-3 otor-pengganti" aria-label=".form-select-sm example" name="tunjuk_otor1_by" id="tunjuk_otor1_by_internal">
                        <option selected disabled> -- Pilih salah satu -- </option> @foreach ($penggantis as $pengganti)
                        @if ($pengganti->satuan_kerja == 2)
                        @if (($pengganti->levelTable->golongan >= 5) && ($pengganti->levelTable->golongan < 7)) @if ($pengganti->levelTable->jabatan == 'Kepala Departemen')
                            <option value="{{ $pengganti['id'] }}">
                                {{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->satuanKerja['inisial']) }} | KA. {{ strtoupper($pengganti->departemenTable['inisial']) }}
                            </option>
                            @else
                            <option value="{{ $pengganti['id'] }}">
                                {{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->satuanKerja['inisial']) }} | {{ strtoupper($pengganti->departemenTable['inisial']) }}
                            </option>
                            @endif
                            @endif
                            @endif
                            @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3" name="pengganti2_antar_satuan_kerja" id="pengganti2_eksternal" style="display: none">
                    <label for="tunjuk_otor2_by" class="form-label">Tanda Tangan 2</label>
                    <select class="form-select mb-3 otor-pengganti" aria-label=".form-select-sm example" name="tunjuk_otor2_by" id="tunjuk_otor2_by_eksternal">
                        <option selected disabled> -- Pilih salah satu -- </option> @foreach ($penggantis as $pengganti)
                        @if ($pengganti->satuan_kerja == 2)
                        @if (($pengganti->levelTable->golongan >= 5) && ($pengganti->levelTable->golongan <= 6)) @if ($pengganti->levelTable->jabatan == 'Kepala Departemen')
                            <option value="{{ $pengganti['id'] }}">
                                {{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->satuanKerja->inisial) }} | KA. {{ strtoupper($pengganti->departemenTable->inisial) }}
                            </option>
                            @else
                            <option value="{{ $pengganti['id'] }}">
                                {{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->satuanKerja->inisial) }} | {{ strtoupper($pengganti->departemenTable->inisial) }}
                            </option>
                            @endif
                            @endif
                            @endif
                            @endforeach
                    </select>
                </div>

                <div class="col-sm-6 mb-3" name="pengganti2_antar_departemen" id="pengganti2_internal" style="display: none">
                    <label for="tunjuk_otor2_by" class="form-label">Tanda Tangan 2</label>
                    <select class="form-select mb-3 otor-pengganti" aria-label=".form-select-sm example" name="tunjuk_otor2_by" id="tunjuk_otor2_by_internal">
                        <option selected disabled> -- Pilih salah satu -- </option> @foreach ($penggantis as $pengganti)
                        @if ($pengganti->satuan_kerja == 2 && $pengganti->departemen == $users->departemen)
                        @if (($pengganti->levelTable->golongan >= 5) && ($pengganti->levelTable->golongan < 6)) <option value="{{ $pengganti['id'] }}">
                            {{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->satuanKerja->inisial) }} | {{ strtoupper($pengganti->departemenTable->inisial) }}
                            </option>
                            @endif
                            @endif
                            @endforeach
                    </select>
                </div>
            </div>

            {{-- Input lampiran --}}
            <div class="mb-3 formulir" style="display: none">
                <label for="berkas" class="form-label">Berkas Memo</label>
                <input class="form-control" type="file" id="berkas" name="berkas">
            </div>

            <!-- <div class="mb-3 formulir" style="display: none;">
                <textarea id="summernote" name="editordata">{{old('editordata')}}</textarea>
            </div> -->

            <div class="mb-3 formulir" style="display: none">
                <label for="lampiran" class="form-label">Lampiran</label>
                <input class="form-control" type="file" id="lampiran" name="lampiran">
            </div>

            <div class="d-flex">
                <!-- <button type="submit" name="lihat" value="lihat" class="btn btn-info formulir" style="display: none" formtarget="_blank">Lihat Pratinjau</button> -->
                <button type="submit" name="draft" value="draft" class="btn btn-warning formulir ms-auto" style="display: none">Simpan Sebagai Draft</button>
                <button type="submit" name="simpan" value="simpan" class="btn btn-primary formulir" style="display: none">Simpan Surat</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('#summernote').summernote({
        placeholder: 'Isi Memo',
        tabsize: 2,
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>
<script>
    $(document).ready(function() {
        $(".form-selectgroup-input").change(function() {
            var tipe = $(".form-selectgroup-input:checked").val();
            if (tipe == 1) {
                $('.formulir').show(1000).delay(100)
                $('.tujuan-eksternal').hide(500)
                $('.tujuan-internal').show(1000)
                $('#pengganti1_eksternal').hide()
                $('#pengganti1_internal').show()
                $('#pengganti2_eksternal').hide()
                $('#pengganti2_internal').show()
                $('#tunjuk_otor1_by_eksternal').val(null).trigger('change')
                $('#tunjuk_otor2_by_eksternal').val(null).trigger('change')
                $('#kantor_cabang').val(null).trigger('change')
                $('#unit_kerja').val(null).trigger('change')
            } else {
                $('.formulir').show(1000).delay(100)
                $('.tujuan-eksternal').show(1000)
                $('.tujuan-internal').hide(500)
                $('#pengganti1_internal').hide()
                $('#pengganti1_eksternal').show()
                $('#pengganti2_internal').hide()
                $('#pengganti2_eksternal').show()
                $('#tunjuk_otor1_by_internal').val(null).trigger('change')
                $('#tunjuk_otor2_by_internal').val(null).trigger('change')
                $('#tujuan_internal').val(null).trigger('change')
            }
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

        $('#tujuan_kantor_cabang').change(function() {
            @foreach($cabangs as $cabang)
            if ($('.besar-{{ $cabang->id }}').is(':selected')) {
                $('.bidang-{{ $cabang->id }}').attr('disabled', 'disabled')
                $('.bidang-{{ $cabang->id }}').prop('selected', false)
            } else {
                $('.bidang-{{ $cabang->id }}').removeAttr('disabled')
                $('.opsi_kantor_cabang_besar').removeAttr('disabled')
            }
            @endforeach
            if ($('#kantor_cabang').is(':selected')) {
                $('.opsi_kantor_cabang_besar').attr('disabled', 'disabled')
                $('.opsi_kantor_cabang_besar').prop("selected", false)
                $('.opsi_kantor_bidang').attr('disabled', 'disabled')
                $('.opsi_kantor_bidang').prop("selected", false)
            }
            //  else {
            //     $('.opsi_kantor_cabang_besar').removeAttr('disabled')
            //     $('.opsi_kantor_bidang').removeAttr('disabled')
            // }
        });

    });
</script>
@endsection