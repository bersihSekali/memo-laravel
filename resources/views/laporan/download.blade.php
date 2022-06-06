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
        <img class="logo" src="data:image/png;base64,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" alt="">
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