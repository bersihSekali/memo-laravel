<!DOCTYPE html>
<html>

<head>
    <style>
        .logo {
            width: 150px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: x-small;
        }

        th,
        td {
            padding: 15px;
        }

        .text {
            font-size: 12px;
        }

        .author {
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- Page Heading -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <img class="logo" src="{{public_path('assets/img/bcasLogo.png')}}" alt="">
        <h3 id="judul">Pencatatan Memo {{ucwords($requests['jenis'])}}</h3>
        <h5 id="tanggal">{{dateWithFormatter($requests['tanggalmulai'])}} s.d. {{dateWithFormatter($requests['tanggalakhir'])}}</h5>
        <hr>
        <br>
        <br>
        <!-- DataTales Example -->
        <table id="tabel-laporan" border="0.5" cellspacing="0">
            <tr>
                <th scope=" col">Tanggal Surat</th>
                @if ($requests['jenis'] == 'masuk')
                <th scope="col">Tanggal Masuk</th>
                @elseif ($requests['jenis'] == 'keluar')
                <th scope="col">Tanggal Keluar</th>
                @endif
                <th scope="col">Disusun Oleh</th>
                <th scope="col">Nomor Surat</th>
                <th scope="col">Perihal</th>
                <th scope="col">Asal</th>
                <th scope="col">Tujuan</th>
                <th scope="col">Status</th>
            </tr>
            @foreach($datas as $data)
            <tr id="data" style="cursor: pointer;">
                <td class="align-top">{{date('Y-m-d', strtotime($data['created_at']))}}</td>
                <td class="align-top">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                <td class="align-top">{{ucwords($data->createdBy['name'])}}</td>
                <td class="align-top">{{$data['nomor_surat']}}</td>
                <td class="align-top">{{$data['perihal']}}</td>
                <td class="align-top">{{$data->satuanKerjaAsal['satuan_kerja']}} | {{$data->departemenAsal['departemen']}}</td>
                <td class="align-top">{{ $data->satuanKerjaTujuan['satuan_kerja'] }} | {{ $data->departemenTujuan['departemen'] }}</td>
                @if($data['status']>1+ $users['level'])
                <td class="align-top text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_sk']))}}</td>
                @else
                <td>Belum diselesaikan</td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <h5>Ringkasan</h5>
        <p class="text">Surat selesai: {{$countSelesai}}</p>
        <p class="text">Surat belum selesai: {{$countBelumSelesai}}</p>
        <hr><br>
        <p class="author">Dikeluarkan oleh: {{$users->name}}</p>
    </div>
    <!-- /.container-fluid -->
</body>

</html>