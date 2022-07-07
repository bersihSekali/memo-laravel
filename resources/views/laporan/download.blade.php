<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin-top: 100px;
            margin-bottom: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            position: fixed;
            top: -70px;
            height: 100%;
            width: 100%;
            line-height: 35px;
        }

        .logo {
            width: 200px;
        }

        table {
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: x-small;
        }

        th {
            background-color: whitesmoke;
        }

        th,
        td {
            padding: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text {
            font-size: 12px;
        }

        .author {
            font-size: 12px;
            text-align: right;
        }

        #judul {
            margin-top: -20px;
        }
    </style>
</head>

<body>
    <header>
        <img class="logo" src="{{ public_path('assets/img/bcasLogo.png') }}" />
    </header>
    <main>
        <div class="container-fluid">
            <!-- Page Heading -->
            <p id="judul">PENCATATAN MEMO {{strtoupper($requests['jenis'])}}</p>
            <p>{{$users->satuanKerja['satuan_kerja']}}</p>
            <p id="tanggal">{{dateWithFormatter($requests['tanggalmulai'])}} s.d. {{dateWithFormatter($requests['tanggalakhir'])}}</p>
            <hr>
            <br>
            <!-- DataTales Example -->
            <table id="tabel-laporan" cellspacing="0">
                <tr>
                    <th scope=" col" width="10%">Tanggal Surat</th>
                    @if ($requests['jenis'] == 'masuk')
                    <th scope="col" width="10%">Tanggal Masuk</th>
                    @elseif ($requests['jenis'] == 'keluar')
                    <th scope="col">Tanggal Keluar</th>
                    @endif
                    <th scope="col" width="10%">Nomor Surat</th>
                    <th scope="col">Perihal</th>
                    <th scope="col" width="10%">Asal</th>
                    <th scope="col">Tujuan</th>
                    <th scope="col" width="10%">Status</th>
                </tr>
                @if ($requests['jenis'] == 'masuk')
                @if ($countSelesai==0 && $countBelumSelesai==0)
                <tr>
                    <td colspan="7" class="text-center">Surat tidak ditemukan untuk rentang tanggal terpilih.</td>
                </tr>
                @else
                @foreach($datas as $data)
                <tr>
                    <td class="text-center">{{date('Y-m-d', strtotime($data['created_at']))}}</td>
                    <td class="text-center">{{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                    <td class="text-center">{{$data['nomor_surat']}}</td>
                    <td>{{$data['perihal']}}</td>
                    <td class="text-center">{{$data->satuanKerjaAsal['inisial']}}</td>
                    <td class="text-center">
                        {{-- Tujuan kantor cabang --}}
                        @if (in_array($data->memo_id, $seluruhCabangMemoIds))
                        SELURUH KANTOR LAYANAN <br>
                        @else
                        @foreach ($tujuanCabangs as $item)
                        @if ($item->memo_id == $data->memo_id)
                        @if ($item->all_flag == true && $item->cabang_id != null)
                        {{ $item->tujuanCabang->cabang }} <br>
                        @endif
                        @endif
                        @endforeach
                        @endif

                        {{-- Tujuan kantor bidang --}}
                        @foreach ($tujuanCabangs as $item)
                        @if ($item->memo_id == $data->memo_id)
                        @if ($item->bidang_id != null && $item->all_flag!=true)
                        {{ $item->tujuanBidang->bidang }} <br>
                        @endif
                        @endif
                        @endforeach

                        {{-- Tujuan satuan kerja --}}
                        @if (in_array($data->memo_id, $seluruhSatkerMemoIds))
                        SELURUH UNIT KERJA KANTOR PUSAT <br>
                        @else
                        @foreach ($tujuanSatkers as $item)
                        @if ($item->memo_id == $data->memo_id)
                        {{ $item->tujuanSatuanKerja['inisial'] }} <br>
                        @endif
                        @endforeach
                        @endif
                    </td>
                    @if($data->tanggal_baca)
                    <td class="text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_baca']))}}</td>
                    @else
                    <td class="text-center">Belum diselesaikan</td>
                    @endif
                </tr>
                @endforeach
                @endif
                <!-- if count selesai -->
                @else
                <!-- else jenis request -->
                @if ($countSelesai==0 && $countBelumSelesai==0)
                <tr>
                    <td colspan="7" class="text-center">Surat tidak ditemukan untuk rentang tanggal terpilih.</td>
                </tr>
                @else
                @foreach($datas as $data)
                <tr>
                    <td class="text-center">{{date('Y-m-d', strtotime($data['created_at']))}}</td>
                    <td class="text-center">{{$data['tanggal_otor1'] ? date('Y-m-d', strtotime($data['tanggal_otor1'])) : '' }}</td>
                    <td class="text-center">{{$data['nomor_surat']}}</td>
                    <td>{{$data['perihal']}}</td>
                    <td class="text-center">{{$data->satuanKerjaAsal['inisial']}}</td>
                    <td class="text-center">
                        {{-- Tujuan satuan kerja --}}
                        @if (in_array($data->id, $seluruhSatkerMemoIds))
                        SELURUH UNIT KERJA KANTOR PUSAT <br>
                        @else
                        @foreach ($tujuanSatkers as $item)
                        @if ($item->memo_id == $data->id)
                        {{ $item->tujuanSatuanKerja['inisial'] }} <br>
                        @endif
                        @endforeach
                        @endif

                        {{-- Tujuan kantor cabang --}}
                        @if (in_array($data->id, $seluruhCabangMemoIds))
                        SELURUH KANTOR LAYANAN <br>
                        @else
                        @foreach ($tujuanCabangs as $item)
                        @if ($item->memo_id == $data->id)
                        @if ($item->all_flag == true && $item->cabang_id != null)
                        {{ $item->tujuanCabang->cabang }} <br>
                        @endif
                        @endif
                        @endforeach
                        @endif

                        {{-- Tujuan kantor bidang --}}
                        @foreach ($tujuanCabangs as $item)
                        @if ($item->memo_id == $data->id)
                        @if ($item->bidang_id != null && $item->all_flag!=true)
                        {{ $item->tujuanBidang->bidang }} <br>
                        @endif
                        @endif
                        @endforeach
                    </td>
                    @if($data->status == 3)
                    <td class="text-center">Selesai pada {{date('Y-m-d', strtotime($data['tanggal_otor1']))}}</td>
                    @else
                    <td class="text-center">Belum terbit</td>
                    @endif
                </tr>
                @endforeach
                @endif
                <!-- if count selesai -->
                @endif
                <!-- if jenis request -->
            </table>
            <br>
            <h5>Ringkasan</h5>
            <p class="text">Surat selesai: {{$countSelesai}}</p>
            <p class="text">Surat belum selesai: {{$countBelumSelesai}}</p>
            <hr><br>
            <p class="author">Dikeluarkan oleh: {{$users->name}}</p>
        </div>
    </main>
    <footer></footer>
    <!-- Page Heading -->
    <!-- /.container-fluid -->
</body>

</html>