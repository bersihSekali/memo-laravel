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

        <form action="/draft/{{$edit['id']}}" method="post" enctype="multipart/form-data">
            @csrf
            {{method_field('PUT')}}

            {{-- Input pembuat --}}
            <div class="form-group mb-3">
                <div class="col">
                    <label for="created_by" class="form-label ">Pembuat</label>
                    <input type="text" class="form-control" autocomplete="off" value="{{ strtoupper($users->name) }}" readonly>
                    <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
                </div>
            </div>

            <input type="radio" name="tipe_surat" value="2" class="form-selectgroup-input" checked hidden>

            {{-- Kriteria --}}
            <div class="form-group formulir mb-3" style="display: none;">
                <div class="col-md-6">
                    <label for="kriteria" class="form-label ">Kriteria Informasi</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="kriteria" style="width: 100%;">
                        <option disabled> -- Pilih salah satu -- </option>
                        <option value="INTERNAL BCA SYARIAH" {{$edit['kriteria'] == "INTERNAL BCA SYARIAH" ? 'selected' : ''}}> INTERNAL BCA SYARIAH </option>
                        <option value="RAHASIA" {{$edit['kriteria'] == "RAHASIA" ? 'selected' : ''}}> RAHASIA </option>
                        <option value="SANGAT RAHASIA" {{$edit['kriteria'] == "SANGAT RAHASIA" ? 'selected' : ''}}> SANGAT RAHASIA </option>
                    </select>
                </div>
            </div>

            {{-- No Surat --}}
            <div class="form-group formulir mb-3" style="display: none;">
                <div class="col-md-6">
                    <label for="nomor_surat" class="form-label ">Nomor Surat</label>
                    <input type="text" class="form-control" autocomplete="off" name="nomor_surat" value="{{$edit['nomor_surat']}}">
                </div>
            </div>

            {{-- Input departemen / satuan kerja --}}
            <div class=" form-group row formulir" style="display: none">
                <div class="col-sm-6 mb-3" id="eksternal">
                    @if ($users->cabang)
                    <label for="cabang_asal" class="form-label">Kantor Cabang Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="cabang_asal" id="cabang_asal" style="width: 100%;">
                        <option selected value="{{ $users->cabang}}"> {{ $users->cabangTable['cabang'] }} </option>
                    </select>
                    @elseif ($users->satuanKerja['grup'] == 5)
                    <label for="satuan_kerja_asal" class="form-label">Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal" style="width: 100%;">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                    @else
                    <label for="satuan_kerja_asal" class="form-label">Satuan Kerja Asal</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="satuan_kerja_asal" id="satuan_kerja_asal" style="width: 100%;">
                        <option selected value="{{ $users->satuan_kerja}}"> {{ $users->satuanKerja['satuan_kerja'] }} </option>
                    </select>
                    @endif
                </div>
            </div>

            {{-- Input tujuan --}}
            <div class="form-group mb-3">
                <label for="tujuan" class="form-label formulir" style="display: none">Tujuan</label>
                {{-- Tujuan unit kerja --}}
                <div class="row justify-content-end formulir tujuan-eksternal" style="display: none">
                    <div class="row">
                        <label for="">Unit Kerja</label>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col">
                            <select class="form-select" aria-label=".form-select-sm example" name="tujuan_unit_kerja[]" id="tujuan_unit_kerja" multiple="multiple">
                                <option id="unit_kerja" value="unit_kerja">Seluruh Unit Kerja</option>
                                @foreach ($satuanKerjas as $satuanKerja)
                                <option class="opsi_unit_kerja" value="{{ $satuanKerja->id }}" {{ in_array($satuanKerja->id, $tujuanSatkerDrafts) ? 'selected' : '' }}>{{ $satuanKerja->inisial }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tujuan unit layanan --}}
                <div class="row justify-content-end formulir tujuan-eksternal" style="display: none">
                    <div class="row mt-2">
                        <label for="">Kantor Cabang / Unit Layanan</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" aria-label=".form-select-sm example" name="tujuan_kantor_cabang[]" id="tujuan_kantor_cabang" multiple="multiple">
                                <option id="kantor_cabang" value="kantor_cabang">Seluruh Kantor Layanan</option>
                                @foreach ($cabangs as $cabang)
                                <option class="opsi_kantor_cabang_besar besar-{{ $cabang->id }}" value="{{ $cabang->id }}" {{ in_array($cabang->id, $tujuanCabangDrafts) ? 'selected' : '' }}>
                                    {{ $cabang->cabang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input perihal --}}
            <div class="form-group mb-3 formulir" style="display: none">
                <label for="perihal" class="form-label ">Perihal</label>
                <textarea class="form-control" aria-label="With textarea" name="perihal" id="perihal" required>{{$edit['perihal']}}</textarea>
            </div>

            {{-- Input otor pengganti --}}
            <div class="form-group row" id="otor_pengganti" name="otor_pengganti" style="display: none">
                <div class="col-sm-6 mb-3" name="pengganti_antar_satuan_kerja" id="pengganti_antar_satuan_kerja" style="display: none;">
                    <label for="tunjuk_otor1_by" class="form-label">Tanda Tangan 1</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor1_by">
                        <option disabled> -- Pilih salah satu -- </option>
                        @if ($users->satuanKerja['grup'] == 5)
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->level == 6 && $pengganti->satuan_kerja == $users->satuan_kerja)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor1_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->satuanKerja['inisial']) }}</option>
                        @endif
                        @endforeach
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->level == 6 && $pengganti->satuanKerja['grup'] == 5 && $pengganti->satuan_kerja != $users->satuan_kerja)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor1_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->satuanKerja['inisial']) }}</option>
                        @endif
                        @endforeach
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->level == 2)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor1_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->satuanKerja['inisial']) }}</option>
                        @endif
                        @endforeach

                        @elseif ($users->cabang)
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->level == 5 || $pengganti->level == 9 || ($pengganti->departemenTable['inisial'] == 'SBK' && $pengganti->level == 2))
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor1_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }}</option>
                        @endif
                        @endforeach

                        @else
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->levelTable->golongan == 7)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor1_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->satuanKerja->satuan_kerja) }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="tunjuk_otor2_by" class="form-label">Tanda Tangan 2</label>
                    <select class="form-select mb-3" aria-label=".form-select-sm example" name="tunjuk_otor2_by" id="tunjuk_otor2_by">
                        <option disabled> -- Pilih salah satu -- </option>
                        @if ($users->cabang)
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->cabang == $users->cabang)
                        @if ($pengganti->level == 5 || $pengganti->level == 9 || $pengganti->level == 10 || $pengganti->level == 12 || $pengganti->level == 13)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor2_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }}</option>
                        @endif
                        @endif
                        @endforeach

                        @elseif ($users->satuanKerja['grup'] == 5)
                        @foreach ($penggantis as $pengganti)
                        @if ($pengganti->satuan_kerja == $users->satuan_kerja)
                        @if ($pengganti->levelTable['golongan'] >= 4 && $pengganti->level != 6)
                        <option value="{{ $pengganti['id'] }}" {{ $edit->otor2_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }}</option>
                        @endif
                        @endif
                        @endforeach

                        @else
                        @foreach ($penggantis as $pengganti)
                        @if (($pengganti->levelTable->golongan == 6) || ($pengganti->levelTable->golongan == 5))
                        @if ($pengganti->satuan_kerja == $users->satuan_kerja)
                        @if ($pengganti->level == 6)
                        <option value="{{ $pengganti['id']}}" {{ $edit->otor2_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - KA. {{ strtoupper($pengganti->departemenTable['departemen']) }}</option>
                        @else
                        <option value="{{ $pengganti['id']}}" {{ $edit->otor2_by == $pengganti['id'] ? 'selected' : '' }}>{{ strtoupper($pengganti->name) }} - {{ strtoupper($pengganti->departemenTable['departemen']) }}</option>
                        @endif
                        @endif
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            {{-- Input lampiran --}}
            <div class="mb-3 formulir" style="display: none">
                <label for="berkas" class="form-label">Berkas Memo</label>
                @if ($edit['berkas'])
                <a href="/storage/{{ $edit['berkas'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Berkas Terunggah</button></a>
                @endif
                <input class="form-control" type="file" id="berkas" name="berkas">
            </div>

            <!-- <div class="mb-3 formulir" style="display: none;">
                <textarea id="summernote" name="editordata">{{old('editordata')}}</textarea>
            </div> -->

            <div class="mb-3 formulir" style="display: none">
                <label for="lampiran" class="form-label">Lampiran</label>
                @if ($edit['lampiran'])
                <a href="/storage/{{ $edit['lampiran'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran Terunggah</button></a>
                @endif
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
        $('.formulir').show(1000)
        $('#eksternal').show(500)
        $('#pengganti_antar_satuan_kerja').show();
        $('#otor_pengganti').show(1000);

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
        });
    });
</script>
@endsection