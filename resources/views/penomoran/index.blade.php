@extends('templates.index')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 mb-5">
        @if(session()->has('error'))
        <div class="alert alert-warning mt-3" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form action="/penomoran" method="post">
            @csrf

            {{-- Input pengambil --}}
            <div class="form-group mb-3">
                <label for="created_by" class="form-label ">PIC Nomor</label>
                <input type="text" class="form-control" autocomplete="off" value="{{ strtoupper($users->name) }}" readonly>
                <input type="hidden" class="form-control" id="created_by" name="created_by" value="{{ $users->id }}" readonly>
            </div>

            {{-- jenis nomor --}}
            <div class="form-group mb-3">
                <label for="jenis" class="form-label ">Jenis Nomor</label>
                <select class="form-select mb-3" aria-label=".form-select-sm example" name="jenis" style="width: 100%;">
                    <option selected disabled> -- Pilih salah satu -- </option>
                    <option value="2"> Satuan Kerja (STL) </option>
                    <option value="1"> Departemen ({{$users->departemenTable['inisial']}}) </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ambil Nomor</button>
        </form>
        <div>
            @if(session()->has('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>
    <h4 class="card-title">Riwayat Pengambilan Nomor</h4>
    <div class="table-responsive">
        <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th class="fs-4" scope="col" width="10%">Tanggal</th>
                    <th class="fs-4" scope="col" width="20%">Riwayat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayats as $riwayat)
                @if ($riwayat->jenis == 1)
                <tr style="color:darkslateblue;">
                    <td>
                        {{$riwayat['created_at']}}
                    </td>
                    <td>
                        {{$riwayat->createdBy['name']}} mengambil nomor {{$riwayat['nomor_surat']}}
                    </td>
                </tr>
                @elseif ($riwayat->jenis == 2)
                <tr style="color:darkgoldenrod;">
                    <td>
                        {{$riwayat['created_at']}}
                    </td>
                    <td>
                        {{$riwayat->createdBy['name']}} mengambil nomor {{$riwayat['nomor_surat']}}
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection