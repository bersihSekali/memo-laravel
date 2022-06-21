@extends('templates.index')

@section('content')
<div class="container">
    <div class="row g-2 align-items-center mb-2">
        <div class="col">
            <h2 class="page-title">
                Daftar Semua Surat
            </h2>
        </div>

        @if ($users->satuan_kerja == 2)
        <div class="col-12 col-md-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="/nomorSurat/create" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Surat
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-body py-3">
            @if(session()->has('success'))
            <div class="alert alert-success" role="alert" id="success-alert" style="display: none">
                {{ session('success') }}
            </div>
            @endif

            <div class="table-responsive">
                <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="fs-4" scope="col" width="10%" style="text-align: center">Tanggal</th>
                            <th class="fs-4" scope="col" width="20%" style="text-align: center">Asal</th>
                            <th class="fs-4" scope="col" style="text-align: center">Perihal</th>
                            <th class="fs-4" scope="col" width="10%" style="text-align: center">Pembuat</th>
                            <th class="fs-4" scope="col" width="8%" style="text-align: center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        @if (($data['satuan_kerja_asal'] == $users['satuan_kerja']))
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                            <td class="align-top" style="text-align: center">
                                @if ($data->departemen_asal == '')
                                {{ $data->satuanKerjaAsal['inisial'] }}
                                @else
                        
                                {{ $data->satuanKerjaAsal['inisial'] }} | {{ $data->departemenAsal['inisial'] }}
                                @endif
                            </td>
                            <td class="align-top">{{ $data->perihal }}</td>
                            <td class="align-top" style="text-align: center">{{ strtoupper($data->createdBy['name'] )}} </td>
                            <td class="align-top">
                                {{-- Setuju --}}
                                @if ($data->status == 3)
                                <span class="badge bg-success">Disetujui</span>

                                {{-- Ditolak antar departemen --}}
                                @elseif ($data->status == 0)
                                <span class="badge bg-danger">Ditolak</span>

                                {{-- Pending --}}
                                @else
                                <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endif
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
                            <td width="20%">Tanggal Registrasi</td>
                            <td>: {{ $data->created_at }}</td>
                        </tr>

                        <tr>
                            <td width="20%">Nomor Surat</td>
                            <td>:
                                @if (!$data->nomor_surat)
                                Setujui surat terlebih dahulu
                                @else
                                {{ $data->nomor_surat }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td width="20%">Pembuat</td>
                            <td>: {{ strtoupper($data->createdBy['name']) }}</td>
                        </tr>

                        <tr>
                            <td width="20%">Asal</td>
                            <td>: {{ $data->satuanKerjaAsal['satuan_kerja'] }} | {{ $data->departemenAsal['departemen'] }}</td>
                        </tr>

                        <tr>
                            <td class="align-top" width="20%">Tujuan</td>
                            <td>

                                {{-- Tujuan kantor cabang --}}
                                @if (in_array($data->id, $seluruhCabangMemoIds))
                                : SELURUH KANTOR LAYANAN <br>
                                @else
                                    @foreach ($tujuanCabangs as $item)
                                        @if ($item->memo_id == $data->id)
                                            @if ($item->all_flag == true && $item->cabang_id != null)
                                            : SELURUH {{ $item->tujuanCabang->cabang }} <br>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Tujuan kantor bidang --}}
                                @foreach ($tujuanCabangs as $item)
                                    @if ($item->memo_id == $data->id)
                                        @if ($item->bidang_id != null && $item->all_flag!=true)
                                        : {{ $item->tujuanBidang->bidang }} <br>
                                        @endif
                                    @endif
                                @endforeach

                                {{-- Tujuan departemen --}}
                                @if (in_array($data->id, $seluruhDepartemenMemoIds))
                                    : SELURUH DEPARTEMEN SKTILOG <br>
                                @else
                                    @foreach ($tujuanDepartemens as $item)
                                        @if ($item->memo_id == $data->id)
                                            : {{ $item->tujuanDepartemen->departemen }} <br>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Tujuan satuan kerja --}}
                                @if (in_array($data->id, $seluruhSatkerMemoIds))
                                    : SELURUH UNIT KERJA KANTOR PUSAT <br>
                                @else
                                    @foreach ($tujuanSatkers as $item)
                                        @if ($item->memo_id == $data->id)
                                            : {{ $item->tujuanSatuanKerja->satuan_kerja }} <br>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>

                        <tr>
                            @if ($data->otor2_by_pengganti && $data->otor1_by_pengganti)
                                <td width="20%">Pejabat Pengganti</td>
                                <td>
                                    : {{ strtoupper($data->otor2ByPengganti->name) }} sebagai otor 2 <br>
                                    {{ strtoupper($data->otor1ByPengganti->name) }} sebagai otor 1
                                </td>
                            @elseif ($data->otor2_by_pengganti && !$data->otor1_by_pengganti)
                                <td width="20%">Pejabat Pengganti</td>
                                <td>
                                    : {{ strtoupper($data->otor2ByPengganti->name) }} sebagai otor 2
                                </td>
                            @elseif (!$data->otor2_by_pengganti && $data->otor1_by_pengganti)
                                <td width="20%">Pejabat Pengganti</td>
                                <td>
                                    : {{ strtoupper($data->otor1ByPengganti->name) }} sebagai otor 1
                                </td>
                            @endif
                        </tr>

                        <tr>
                            <td class="align-top" width="20%">Perihal</td>
                            <td>: {{ $data->perihal }}</td>
                        </tr>

                        @if ($data->status == 0)
                        <tr>
                            <td width="20%">Catatan</td>
                            <td>: {{ $data->pesan_tolak }}</td>
                        </tr>
                        @endif

                        <tr>
                            <td width="20%">Status</td>
                            <td>
                                : @if ($data->status == 1)
                                <span class="badge bg-secondary">Pending</span>

                                {{-- approved otor2_by --}}
                                @elseif ($data->status == 2)
                                {{-- Antar departemen --}}
                                @if ($data->internal == 1)
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-secondary">
                                    Pending Kadep
                                </span>

                                {{-- Antar satuan kerja --}}
                                @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-secondary">
                                    Pending Kasat
                                </span>
                                @endif

                                {{-- approved otor1_by --}}
                                @elseif ($data->status == 3)
                                @if ($data->internal == 1)
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>

                                {{-- Antar satuan kerja --}}
                                @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>
                                @endif

                                {{-- rejected --}}
                                @elseif ($data->status == 0)
                                {{-- rejected otor2_by --}}
                                @if ($data->otor1_by == 0)
                                <span class="badge bg-danger">
                                    Ditolak {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>

                                {{-- rejected otor1_by --}}
                                @else
                                <span class="badge bg-success">
                                    Disetujui {{ strtoupper($data->otor2By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}
                                </span>
                                <span class="badge bg-danger">
                                    Ditolak {{ strtoupper($data->otor1By['name']) }} at: {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}
                                </span>
                                @endif
                                @endif
                            </td>
                        </tr>

                        <tr width="20%">
                            <td>Lampiran</td>
                            <td>: <a href="/storage/{{ $data['lampiran'] }}" target="_blank"><button type="button" class="btn btn-secondary btn-sm" style="text-decoration: none">Lihat Lampiran</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>

                @if ($data->satuan_kerja_asal == $users->satuan_kerja)
                    @if (($data->departemen_asal == $users->departemen) || ($users->level == 2))
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$data['id']}}">Hapus</button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal delete for confirmation --}}
<div class="modal modal-blur fade" id="modal-delete-{{ $data['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>

            <div class="modal-body text-center py-4">
                <h3>Apakah yakin ingin menghapus surat?</h3>
            </div>

            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Tidak
                            </a>
                        </div>

                        <div class="col">
                            <form action="/nomorSurat/{{ $data['id'] }}" method="post">
                                @csrf
                                {{method_field('DELETE')}}

                                <button type="submit" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$data['id']}}">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $('#success-alert').show(1000).delay(1000);
        $('#success-alert').hide(1000);
    });
</script>
@endsection