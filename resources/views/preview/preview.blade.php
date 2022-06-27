<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin-top: 120px;
            margin-bottom: 40px;
            margin-left: 80px;
        }

        main {
            margin-top: 20px;
            page-break-after: always;
            font-size: small;

        }

        header {
            position: fixed;
            top: -110px;
            height: 100%;
            width: 100%;
            line-height: 35px;
        }

        footer {
            position: fixed;
            bottom: -860px;
            height: 100%;
            width: 100%;
            line-height: 35px;
        }

        .footer-text {
            text-align: right;
            font-size: smaller;
        }

        .kop {
            position: fixed;
            top: -70px;
            height: 100%;
            width: 100%;
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
                        @foreach($tujuanSatkers as $tujuanSatker)
                        {{$tujuanSatker}}<br>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td>:</td>
                    <td>{{$dari['satuan_kerja']}}</td>
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
                    <td>INTERNAL BCA SYARIAH</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{dateWithFormatter(date('Y-m-d'))}}</td>
                </tr>
            </table>
            <div style="font-size:small;">
                {!!$requests['isi']!!}
            </div>
            <div>
                <p><strong>{{$dari['satuan_kerja']}}</strong></p>
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
    </main>

    <header>
        <table>
            <tr>
                <td>{{$requests['nomor_surat']}}</td>
                <td>INTERNAL BCA SYARIAH</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal: <strong>{{$requests['perihal']}}</strong></td>
                <td>, Sambungan</td>
            </tr>
        </table>
    </header>
    <footer>
        <p class="footer-text"><i>Bersambung ke halaman berikut</i></p>
    </footer>
    <!-- Page Heading -->
    <!-- /.container-fluid -->
</body>

</html>