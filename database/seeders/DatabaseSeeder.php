<?php

namespace Database\Seeders;

use App\Models\BidangCabang;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Grup;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\SatuanKerja;
use App\Models\Level;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Grup::create([
            'nama_grup' => 'Unit Kerja'
        ]);

        Grup::create([
            'nama_grup' => 'Kantor Cabang'
        ]);

        Grup::create([
            'nama_grup' => 'Departemen'
        ]);

        Grup::create([
            'nama_grup' => 'Departemen Satu Tingkat di Bawah Direksi'
        ]);

        Grup::create([
            'nama_grup' => 'Fungsi'
        ]);

        $satuanKerja = [
            ['SKTILOG', 1], ['SKAI', 1], ['SKHSDM', 1], ['SBK', 1], ['SKARP', 1], ['SKBRK', 1],
            ['MANAJEMEN RISIKO', 4], ['SKKPP', 1], ['DIVISI OPERASI', 1], ['PENGURUS BCA SYARIAH', 1],
            ['KEPATUHAN', 4], ['CABANG JABODETABEK', 1], ['CABANG NON JABODETABEK', 1],
        ];

        for ($i = 0; $i < count($satuanKerja); $i++) {
            SatuanKerja::create([
                'satuan_kerja' => $satuanKerja[$i][0],
                'grup' => $satuanKerja[$i][1]
            ]);
        }

        $departemen = array(
            // [satuan keja, nama departemen, grup]
            // SKTILOG
            [1, 'LOGISTIK', 3], [1, 'SISTEM PROSEDUR & PENDUKUNG OPERASI', 3], [1, 'PENGEMBANGAN TEKNOLOGI INFORMASI', 3],
            [1, 'SEKURITI TEKNOLOGI INFORMASI', 3], [1, 'OPERASI TEKNOLOGI INFORMASI', 5],

            // SKAI
            [2, 'INTERNAL', 3], [2, 'TEKNOLOGI INFORMASI', 3], [2, 'KANTOR PUSAT & ANTI FRAUD', 3],
            [2, 'KANTOR CABANG', 3],

            // SKHSDM
            [3, 'SDM', 3], [3, 'HUKUM', 3], [3, 'BUDAYA PERUSAHAAN DAN LAYANAN', 5],

            // SBK
            [4, 'KOMUNIKASI DAN KESEKRETARIATAN PERUSAHAAN', 3], [4, 'PENDUKUNG BISNIS', 3], [4, 'PENGEMBANGAN BISNIS', 3],
            [4, 'RISET PEMASARAN DAN PENDAYAGUNAAN DATA WAREHOUSE', 5], [4, 'PENGEMBANGAN DAN PEMBINAAN JARINGAN CABANG', 3],

            // SKARP
            [5, 'PENDUKUNG ARP', 5], [5, 'ANALISA PEMBIAYAAN', 5], [5, 'PENILAIAN AGUNAN', 5],

            // SKBRK
            [6, 'PENGEMBANGAN PRODUK DAN PENGELOLAAN PROSES', 5], [5, 'PENGEMBANGAN DAN LAYANAN BISNIS', 5],

            // SKKPP
            [7, 'PERENCANAAN PERUSAHAAN', 5], [7, 'PORTOFOLIO MANAJEMEN', 5], [7, 'BIDANG KEUANGAN PERUSAHAAN', 3],

            // DIVISI OPERASI
            [8, 'SENTRA OPERASI PERBANKAN', 3], [8, 'ADMINISTRASI PEMBIAYAAN', 3], [8, 'PENYELAMATAN PEMBIAYAAN', 5]
        );

        for ($i = 0; $i < count($departemen); $i++) {
            Departemen::create([
                'satuan_kerja' => $departemen[$i][0],
                'departemen' => $departemen[$i][1],
                'grup' => $departemen[$i][2]
            ]);
        }

        $cabang = array(
            // Jabodetabek
            ['SELURUH KANTOR CABANG', 5], ['JATINEGARA', 5], ['MANGGA DUA', 5],
            ['SAMANHUDI', 5], ['SUNTER', 5],

            // Non jabodetabek
            ['BANDA ACEH', 6], ['BANDUNG', 6], ['LAMPUNG', 6],
            ['MEDAN', 6], ['PALEMBANG', 6], ['PANAKKUKANG', 6],
            ['SEMARANG', 6], ['SOLO', 6], ['SURABAYA', 6], ['YOGYAKARTA', 6]
        );

        for ($i = 0; $i < count($cabang); $i++) {
            Cabang::create([
                'cabang' => $cabang[$i][0],
                'satuan_kerja_id' => $cabang[$i][1]
            ]);
        }

        $bidang = array(
            // [Unit layanan, Cabang Utama]
            // Jatinegara
            ['CABANG JATINEGARA', 2], ['KCP DEPOK', 2], ['KCP CILEUNGSI', 2], ['ULS CIMANGGIS', 2],
            ['ULS MARGONDA DEPOK', 2], ['ULS BOGOR', 2], ['ULS GUDANG PELURU', 2],
            ['ULS PONDOK INDAH', 2], ['ULS PASAR MINGGU', 2],

            // Mangga dua
            ['CABANG MANGGA DUA', 3], ['KCP CILEDUG', 3], ['KCP CIPUTAT', 3],
            ['ULS TANAH ABANG', 3], ['ULS BINTARO UTAMA', 3], ['ULS MELAWAI', 3],
            ['ULS KEMANG MANSION', 3],

            // Samanhudi
            ['CABANG SAMANHUDI', 4], ['KCP KENARI', 4], ['KCP PASAR ANYAR TANGERANG', 4],
            ['ULS TANGERANG', 4], ['ULS PLUTI KENCANA', 4], ['ULS PURI INDAH', 4],
            ['ULS BINTARO', 4],

            // Sunter
            ['CABANG SUNTER', 5], ['KCP KELAPA GADING', 5], ['KCP BEKASI', 5],
            ['KCP PASAR KRANJI BEKASI', 5], ['KCP PONDOK GEDE', 5], ['KCP CIKARANG SELATAN', 5],
            ['ULS JUANDA BEKASI', 5], ['ULS TANJUNG PRIOK', 5],

            // Banda aceh
            ['CABANG BANDA ACEH', 6], ['KCP LHOKSEUMAWE', 6], ['ULS BIREUEN', 6],

            // Bandung
            ['CABANG BANDUNG', 7], ['ULS BUAH BATU', 7], ['ULS KOTA BARU PARAHYANGAN', 7],
            ['ULS DAGO', 7],

            // Lampung
            ['CABANG LAMPUNG', 8],

            // Medan
            ['CABANG MEDAN', 9], ['ULS SETIABUDI MEDAN', 9],

            // Palembang
            ['CABANG PALEMBANG', 10], ['ULS SUDIRMAN PALEMBANG', 10], ['ULS AHMAD RIVAI', 10],

            // Panakkukang
            ['CABANG PANAKKUKANG', 11],

            // Semarang
            ['CABANG SEMARANG', 12], ['ULS PEMUDA', 12], ['ULS KUDUS', 12], ['ULS MAJAPAHIT', 12],

            // Solo
            ['CABANG SOLO', 13], ['ULS SLAMET TIYADI', 13], ['ULS SINGOSAREN', 13], ['ULS SRAGEN', 13],

            // Surabaya
            ['CABANG SURABAYA', 14], ['KCP MALANG', 14], ['KCP KEDIRI', 14],
            ['KCP PASURUAN', 14], ['KCP BANYUWANGI', 14], ['ULS VETERAN', 14],
            ['ULS DARMO', 14], ['ULS GEDANGAN', 14], ['ULS SEPANJANG', 14],
            ['ULS PERAK BARAT', 14], ['ULS SIDOARJO', 14], ['ULS PONDOK CHANDRA', 14],
            ['ULS PANDAAN', 14], ['ULS GRESIK', 14], ['ULS MOJOKERTO', 14],
            ['ULS KAPAS KRAMPUNG', 14], ['ULS KEPANJEN', 14], ['ULS TAMAN PONDOK INDAH', 14],

            // Yogyakarta
            ['CABANG YOGYAKARTA', 15], ['ULS SUDIRMAN YOGYAKARTA', 15]
        );

        for ($i = 0; $i < count($bidang); $i++) {
            BidangCabang::create([
                'bidang' => $bidang[$i][0],
                'cabang_id' => $bidang[$i][1]
            ]);
        }

        $level = array(
            ['admin', 99], ['Kepala Satuan Kerja', 7], ['Kepala Divisi', 7],
            ['Kepala Unit Kerja', 7], ['Kepala Cabang', 6], ['Kepala departemen', 6],
            ['Senior Officer', 6], ['Kepala Bidang', 5], ['Kepala Operasi Cabang', 5],
            ['Kepala Cabang Pembantu', 5], ['Officer', 5], ['Kepala Bagian', 4],
            ['Kepala ULS A', 4], ['Associate Officer', 4], ['Staff', 3]
        );

        for ($i = 0; $i < count($level); $i++) {
            Level::create([
                'jabatan' => $level[$i][0],
                'golongan' => $level[$i][1]
            ]);
        }

        $user = array(
            // [name, level, satuan kerja, departemen, id telegram]
            ['klin', 1, null, null, null], ['agusta', 2, 1, null, null], ['hernandi', 6, 1, 1, 986550971],
            ['james', 7, 1, 1, null], ['yudhi', 8, 1, 1, 1315801671], ['nur', 12, 1, 1, null],
            ['bayu', 15, 1, 1, null], ['dathu', 6, 1, 2, null], ['riyadi', 7, 1, 2, null],
            ['kertayuga', 8, 1, 2, null], ['eka', 12, 1, 2, null], ['prasetyo', 15, 1, 2, null],
            ['ariefin', 6, 1, 3, null], ['thor', 7, 1, 3, null], ['hatta', 8, 1, 3, 267195734],
            ['febriansyah', 12, 1, 3, null], ['efrinaldi', 15, 1, 3, null], ['alzuhri', 2, 2, null, null],
            ['trinandani', 6, 2, 6, null], ['ragnarok', 7, 2, 6, null], ['githa', 8, 2, 6, null],
            ['refina', 12, 2, 6, null], ['muhammad', 15, 2, 6, null], ['afta', 6, 2, 7, null],
            ['buddin', 7, 2, 7, null], ['komo', 8, 2, 7, null], ['arsyad', 12, 2, 7, null],
            ['risky', 15, 2, 7, null], ['septiawan', 2, 3, null, null], ['john', 6, 3, 10, null],
            ['lennon', 7, 3, 10, null], ['fredy', 8, 3, 10, null], ['mercury', 12, 3, 10, null],
            ['venus', 15, 3, 10, null], ['bumi', 6, 3, 11, null], ['mars', 7, 3, 11, null],
            ['jupiter', 8, 3, 11, null], ['saturn', 12, 3, 11, null], ['uranus', 15, 3, 11, null],
            ['neptunus', 2, 4, null, null], ['pluto', 6, 4, 13, null], ['alda', 7, 4, 13, null],
            ['tri', 8, 4, 13, null], ['risma', 12, 4, 13, null], ['maharini', 15, 4, 13, null],
            ['basuki', 6, 4, 14, null], ['cahaya', 7, 4, 14, null], ['bulan', 8, 4, 14, null],
            ['purnama', 12, 4, 14, null], ['sabit', 15, 4, 14, null]
        );

        for ($i = 0; $i < count($user); $i++) {
            $data = [
                'name' => $user[$i][0],
                'level' => $user[$i][1],
                'satuan_kerja' => $user[$i][2],
                'departemen' => $user[$i][3],
                'id_telegram' => $user[$i][4],
                'password' => hash::make('Syariah@1')
            ];
            User::create($data);
        }
    }
}
