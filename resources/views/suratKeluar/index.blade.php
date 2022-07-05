@extends('templates.index')

@section('content')
<div class="container">
    <div class="row g-2 align-items-center mb-2">
        <div class="col">
            <h2 class="page-title">
                Daftar Semua Surat
            </h2>
        </div>

        <div class="col-12 col-md-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="/suratKeluar/create" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Surat
                </a>
            </div>
        </div>
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
                            <th class="fs-4" scope="col" width="10%">Tanggal</th>
                            <th class="fs-4" scope="col" width="20%">Asal</th>
                            <th class="fs-4" scope="col">Perihal</th>
                            <th class="fs-4" scope="col" width="10%">Pembuat</th>
                            <th class="fs-4" scope="col" width="8%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr id="data" data-bs-toggle="modal" data-bs-target="#mail-{{$data['id']}}" style="cursor: pointer;">
                            <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                            <td class="align-top">
                                @if ($data->satuan_kerja_asal)
                                {{ $data->satuanKerjaAsal['inisial'] }}
                                @else
                                CABANG {{ $data->cabangAsal['cabang'] }}
                                @endif
                            </td>
                            <td class="align-top">{{ $data->perihal }}</td>
                            <td class="align-top">{{ strtoupper($data->createdBy['name'] )}} </td>
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
                            <td>
                                @if ($data->satuan_kerja_asal)
                                : {{ $data->satuanKerjaAsal['satuan_kerja'] }}
                                @else
                                : CABANG {{ $data->cabangAsal['cabang'] }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="align-top" width="20%">Tujuan</td>
                            <td>
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

                                {{-- Tujuan kantor cabang --}}
                                @if (in_array($data->id, $seluruhCabangMemoIds))
                                : SELURUH KANTOR LAYANAN <br>
                                @else
                                @foreach ($tujuanCabangs as $item)
                                @if ($item->memo_id == $data->id)
                                @if ($item->all_flag == true && $item->cabang_id != null)
                                : Cabang {{ $item->tujuanCabang->cabang }} <br>
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

                        <tr width="20%">
                            <td>Isi</td>
                            @if ($data->status == 0)
                            <td>: <a type="button" href="/storage/{{ $data['lampiran_tolak'] }}" class="btn btn-info btn-sm" style="text-decoration: none" target="_blank">Lihat Memo</a></td>
                            @else
                            <td>: <a type="button" href="/draft/{{ $data['id'] }}" class="btn btn-info btn-sm" style="text-decoration: none" target="_blank">Lihat Memo</a></td>
                            @endif
                        </tr>
                        @if ($data['lampiran'])
                        <tr width="20%">
                            <td>Lampiran</td>
                            <td>: <a class="btn btn-info btn-sm" href="/storage/{{ $data['lampiran'] }}" target="_blank">Lihat Lampiran</a></td>
                        </tr>
                        @endif
                        <tr>
                            <td width="20%">Status</td>
                            <td>
                                : @if ($data->status == 1)
                                <span class="badge bg-secondary">Menunggu {{$data->otor2By['name']}}</span>
                                <span class="badge bg-secondary">Menunggu {{$data->otor1By['name']}}</span>

                                {{-- approved otor2_by --}}
                                @elseif ($data->status == 2)
                                <span class="badge bg-success">Disetujui {{$data->otor2By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}</span>
                                <span class="badge bg-secondary">Menunggu {{$data->otor1By['name']}}</span>

                                {{-- approved otor1_by --}}
                                @elseif ($data->status == 3)
                                <span class="badge bg-success">Disetujui {{$data->otor2By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}</span>
                                <span class="badge bg-success">Disetujui {{$data->otor1By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_otor1)) }}</span>

                                {{-- rejected --}}
                                @elseif ($data->status == 0)
                                @if ($data->tanggal_otor2)
                                <span class="badge bg-success">Disetujui {{$data->otor2By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_otor2)) }}</span>
                                <span class="badge bg-danger">Ditolak {{$data->otor1By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_tolak)) }}</span>
                                @else
                                <span class="badge bg-danger">Ditolak {{$data->otor2By['name']}} pada {{ date("Y-m-d", strtotime($data->tanggal_tolak)) }}</span>
                                @endif
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @if ($data->status == 0)
            <div class="modal-footer">
                <a class="btn btn-warning" href="/draft/{{ $data['id'] }}/edit">Edit</a>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$data['id']}}">Hapus</button>
            </div>
            @endif
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