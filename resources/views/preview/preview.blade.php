<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin-top: 120px;
            margin-bottom: 60px;
            margin-left: 80px;
        }

        main {
            margin-top: 20px;
            font-size: small;
        }

        header {
            position: fixed;
            top: -110px;
            height: 100%;
            width: 100%;
            line-height: 35px;
            z-index: -2;
        }

        footer {
            position: fixed;
            font-size: small;
            bottom: -860px;
            height: 100%;
            width: 100%;
            line-height: 35px;
            z-index: -1;
        }

        .footer {
            position: fixed;
            height: 100%;
            width: 100%;
            bottom: -875px;
            color: white;
            background-color: white;
        }

        .footer-text {
            text-align: right;
            font-size: smaller;
        }

        .kop {
            position: fixed;
            padding-top: 30px;
            top: -100px;
            height: 5%;
            width: 100%;
            background-color: white;
        }

        #judul {
            font-size: larger;
            text-align: center;
            margin: 0;
        }

        #nomor {
            text-align: center;
            margin-top: 0;
        }

        #kepada {
            margin-bottom: 50px;
        }

        #isi {
            font-size: small;
            margin-bottom: 50px;
        }

        #ttd {
            width: 60%;
        }

        #kolom-ttd {
            height: 40px;
        }

        .align-top {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <footer>
        <p class="footer-text"><i>Bersambung ke halaman berikut</i></p>
    </footer>
    <main>
        <div class="container-fluid">
            <div class="kop">
                <!-- Page Heading -->
                <p id="judul"><u>M E M O R A N D U M</u></p>
                <p id="nomor">No.: {{$requests['nomor_surat']}}</p>
                <br>
                <br>
            </div>
            <table id="kepada">
                <tr>
                    <td class="align-top" width="10%"> Kepada</td>
                    <td class="align-top">:</td>
                    <td>
                        @if ($requests['internal'] == 1)
                        @foreach($tujuanDepartemens as $tujuanDepartemen)
                        {{$tujuanDepartemen}}<br>
                        @endforeach
                        @else
                        @foreach($tujuanSatkers as $tujuanSatker)
                        {{$tujuanSatker}}<br>
                        @endforeach
                        @foreach($tujuanCabangs as $tujuanCabang)
                        {{$tujuanCabang}}<br>
                        @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td>:</td>
                    <td>{{$dari}}</td>
                </tr>
                <tr>
                    <td>Tembusan</td>
                    <td>:</td>
                    <td>Satuan Kerja Tembusan</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td><strong>{{$requests['perihal']}}</strong></td>
                </tr>
                <tr>
                    <td>Kriteria Informasi</td>
                    <td>:</td>
                    <td>{{$requests['kriteria']}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{dateWithFormatter(date('Y-m-d'))}}</td>
                </tr>
            </table>
            <div id="isi">
                {!!$requests['isi']!!}
            </div>
            <div>
                <p><strong>{{strtoupper($dari)}}</strong></p>
                <table id="ttd">
                    <tr id="kolom-ttd">
                        <td><br><br></td>
                        <td><br><br></td>
                    </tr>
                    <tr>
                        <td>
                            <p><u>{{strtoupper($ttd1['name'])}}</u><br>
                                {{$jabatanTtd1}}
                            </p>
                        </td>
                        <td>
                            <p><u>{{strtoupper($ttd2['name'])}}</u><br>
                                {{$jabatanTtd2}}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <p class="footer-text"><i>Bersambung ke halaman berikut</i></p>
        </div>
    </main>
    <header>
        <table>
            <tr>
                <td>{{$requests['nomor_surat']}}</td>
                <td>{{$requests['kriteria']}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">Perihal: <strong>{{$requests['perihal']}}</strong>, Sambungan</td>
            </tr>
        </table>
    </header>

    <!-- Page Heading -->
    <!-- /.container-fluid -->
</body>

</html>