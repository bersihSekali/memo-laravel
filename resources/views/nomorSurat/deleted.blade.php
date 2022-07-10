@extends('templates.index')

@section('content')
<div class="container">
    <div class="row g-2 align-items-center mb-2">
        <div class="col">
            <h2 class="page-title">
                Daftar Surat Terhapus
            </h2>
        </div>

        <div class="col-12 col-md-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="/nomorSurat/hapusPermanen" class="btn btn-danger d-none d-sm-inline-block">
                    <i class="fas fa-dumpster-fire"></i>
                    Hapus Permanen
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table id="tabel-data" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col" width="10%">Tanggal Terbit</th>
                            <th scope="col">No.</th>
                            <th scope="col">Asal</th>
                            <th scope="col">Tujuan</th>
                            <th scope="col">Perihal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">
                                @if ($data->tanggal_otor1)
                                {{date('Y-m-d', strtotime($data['tanggal_otor1']))}}
                                @endif
                            </td>
                            <td class="align-top">{{$data['nomor_surat']}}</td>
                            <td class="align-top">
                                @if ($data->internal == 1)
                                {{$data->satuanKerjaAsal['inisial']}} | {{$data->departemenAsal['inisial']}}
                                @elseif ($data->cabang_asal)
                                {{$data->cabangAsal['cabang']}}
                                @else
                                {{$data->satuanKerjaAsal['inisial']}}
                                @endif
                            </td>
                            <td>
                                {{-- Tujuan satuan kerja --}}
                                @if (in_array($data->id, $seluruhSatkerMemoIds))
                                Segenap Unit Kerja Kantor Pusat <br>
                                @else
                                @foreach ($tujuanSatkers as $item)
                                @if ($item->memo_id == $data->id)
                                {{ $item->tujuanSatuanKerja['inisial'] }} <br>
                                @endif
                                @endforeach
                                @endif

                                {{-- Tujuan kantor cabang --}}
                                @if (in_array($data->id, $seluruhCabangMemoIds))
                                Segenap Kantor Layanan <br>
                                @else
                                @foreach ($tujuanCabangs as $item)
                                @if ($item->memo_id == $data->id)
                                {{ $item->tujuanCabang->cabang }} <br>
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td class="align-top">{{$data['perihal']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal For Showing Detail Data -->
@foreach($datas as $data)
<div class="modal modal-blur fade" id="mail-{{$data['id']}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detil Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabel-data" style="width:100%">
                        <tr>
                            <td width="20%">Tanggal Surat</td>
                            <td>: {{ date('Y-m-d', strtotime($data->created_at)) }}</td>
                        </tr>

                        <tr>
                            <td>Tanggal Tanda Tangan 2</td>
                            <td>
                                @if ($data->tanggal_otor2)
                                : {{date('Y-m-d', strtotime($data['tanggal_otor2']))}}
                                @else
                                :
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Tanggal Tanda Tangan 1</td>
                            <td>
                                @if ($data->tanggal_otor1)
                                : {{date('Y-m-d', strtotime($data['tanggal_otor1']))}}
                                @else
                                :
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Nomor Surat</td>
                            <td>: {{ $data->nomor_surat }}</td>
                        </tr>

                        <tr>
                            <td>Disusun Oleh</td>
                            <td>: {{strtoupper($data->createdBy['name'])}}</td>
                        </tr>

                        <tr>
                            <td>Asal</td>
                            @if ($data->cabang_asal)
                            <td>: {{$data->cabangAsal['cabang']}}</td>
                            @elseif($data->internal == 1)
                            <td>: {{ $data->satuanKerjaAsal['inisial'] }} | {{ $data->departemenAsal['inisial'] }}</td>
                            @elseif($data->internal ==2)
                            <td>: {{ $data->satuanKerjaAsal['inisial'] }}</td>
                            @endif

                        </tr>

                        <tr>
                            <td class="align-top" width="20%">Tujuan</td>
                            <td>

                                {{-- Tujuan kantor cabang --}}
                                @if (in_array($data->memo_id, $seluruhCabangMemoIds))
                                : Segenap Kantor Layanan <br>
                                @else
                                @foreach ($tujuanCabangs as $item)
                                @if ($item->memo_id == $data->memo_id)
                                : {{ $item->tujuanCabang->cabang }} <br>
                                @endif
                                @endforeach
                                @endif

                                {{-- Tujuan satuan kerja --}}
                                @if (in_array($data->memo_id, $seluruhSatkerMemoIds))
                                : Segenap Unit Kerja Kantor Pusat <br>
                                @else
                                @foreach ($tujuanSatkers as $item)
                                @if ($item->memo_id == $data->memo_id)
                                : {{ $item->tujuanSatuanKerja['inisial'] }} <br>
                                @endif
                                @endforeach
                                @endif

                                {{-- Tujuan departemen --}}
                                @if (in_array($data->memo_id, $seluruhDepartemenMemoIds))
                                : Seluruh Departemen SKTILOG <br>
                                @else
                                @foreach ($tujuanDepartemens as $item)
                                @if ($item->memo_id == $data->memo_id)
                                : {{ $item->tujuanDepartemen['inisial'] }} <br>
                                @endif
                                @endforeach
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Perihal</td>
                            <td>: {{ $data->perihal }}</td>
                        </tr>

                        <tr>
                            <td>Berkas</td>
                            <td>: <a class="btn btn-info btn-sm" href="/storage/{{ $data['berkas'] }}" target="_blank">Lihat Memo</a></td>
                        </tr>

                        @if ($data['isi'])
                        <tr width="20%">
                            <td>Isi</td>
                            <td>: <a type="button" href="/draft/{{ $data['memo_id'] }}" class="btn btn-info btn-sm" style="text-decoration: none" target="_blank">Lihat Memo</a></td>
                        </tr>
                        @endif

                        @if($data['lampiran'])
                        <tr>
                            <td>Lampiran</td>
                            <td>: <a class="btn btn-info btn-sm" href="/storage/{{ $data['lampiran'] }}" target="_blank">Lihat Lampiran</a></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection