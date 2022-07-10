@extends('templates.index')

@section('content')
<div class="container">
    <div class="row g-2 align-items-center mb-2">
        <div class="col">
            <h2 class="page-title">
                Data Satuan Kerja
            </h2>
        </div>

        <div class="col-12 col-md-auto ms-auto d-print-none">
            <div class="row">
                <div class="col-6 btn-list">
                    <a href="/satuanKerja/create" class="btn btn-primary d-none d-sm-inline-block">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Satuan Kerja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body py-3">
            @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <div class="table-responsive">
                <table id="tabel-list" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="fs-4" scope="col" style="text-align: center">Satuan Kerja</th>
                            <th class="fs-4" scope="col" style="text-align: center">Inisial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($satuanKerjas as $satuanKerja)
                        @if ($satuanKerja->id == 1)
                        @continue
                        @endif
                        <tr>
                            <td class="align-top">{{ $satuanKerja->satuan_kerja }}</td>
                            <td class="align-top">{{ $satuanKerja->inisial }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection