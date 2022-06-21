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

        $grup = array(
            'Unit Kerja', 'Kantor Cabang Utama', 'Kantor Cabang Pembantu', 'Departemen',
            'Departemen Satu Tingkat Dibawah Direksi', 'Fungsi'
        );

        for ($i = 0; $i < count($grup); $i++) {
            Grup::create([
                'nama_grup' => $grup[$i]
            ]);
        }

        $satuanKerja = [
            // [Satuan kerjam, inisial, grup]
            ['SELURUH SATUAN KERJA / DIVISI', 'ALL', 1], ['SATUAN KERJA TEKNOLOGI INFORMASI DAN LOGISTIK', 'STL', 1], ['SATUAN KERJA AUDIT INTERNAL', 'SAI', 1],
            ['SATUAN KERJA HUKUM DAN SUMBER DAYA MANUSIA', 'HSD', 1], ['SATUAN KERJA BISNIS DAN KOMUNIKASI', 'SBK', 1], ['SATUAN KERJA ANALISA RISIKO DAN PEMBIAYAAN', 'ARP', 1],
            ['SATUAN KERJA KEUANGAN DAN PERENCANAAN PERUSAHAAN', 'SKA', 1],
            ['DEPARTEMEN MANAJEMEN RISIKO', 'MRK', 5], ['DIVISI OPERASI', 'DOP', 1],
            ['DEPARTEMEN KEPATUHAN', 'KEP', 5], ['CABANG JABODETABEK', null, 1], ['CABANG NON JABODETABEK', null, 1],
        ];

        for ($i = 0; $i < count($satuanKerja); $i++) {
            SatuanKerja::create([
                'satuan_kerja' => $satuanKerja[$i][0],
                'inisial' => $satuanKerja[$i][1],
                'grup' => $satuanKerja[$i][2]
            ]);
        }

        $departemen = array(
            // [satuan keja, nama departemen, inisial, grup]
            // Sluruh satuan kerja internal sktilog
            [1, 'SELURUH DEPARTEMEN SKTILOG', 'STL-ALL', 1],
            // SKTILOG
            [2, 'LOGISTIK', 'LOG', 4], [2, 'SISTEM PROSEDUR & PENDUKUNG OPERASI', 'PPO', 4], [2, 'DEPARTEMEN TEKNOLOGI INFORMASI', 'DTI', 4],
            // [2, 'SEKURITI TEKNOLOGI INFORMASI', 3], [2, 'OPERASI TEKNOLOGI INFORMASI', 5],

            // SKAI
            // [3, 'INTERNAL', 3], [3, 'TEKNOLOGI INFORMASI', 3], 
            [3, 'DEPARTEMEN KANTOR PUSAT & ANTI FRAUD', 'AKP', 4],
            [3, 'DEPARTEMEN AUDIT KANTOR CABANG DAN INTERNAL CONTROL', 'AKC', 4],

            // SKHSDM
            [4, 'DEPARTEMEN SUMBER DAYA MANUSIA', 'SDM', 4], [4, 'DEPARTEMEN HUKUM', 'DHK', 4],
            // [4, 'BUDAYA PERUSAHAAN DAN LAYANAN', 5],

            // SBK
            [5, 'DEPARTEMEN KOMUNIKASI DAN KESEKRETARIATAN PERUSAHAAN', 'KSP', 4], [5, 'DEPARTEMEN PENDUKUNG BISNIS', 'PDS', 4], [5, 'DEPARTEMEN PENGEMBANGAN BISNIS', 'PBS', 4],
            // [5, 'RISET PEMASARAN DAN PENDAYAGUNAAN DATA WAREHOUSE', 5], [5, 'PENGEMBANGAN DAN PEMBINAAN JARINGAN CABANG', 3],

            // SKARP
            [6, 'FUNGSI PENDUKUNG ARP', 'RPD', 6],
            // [6, 'ANALISA PEMBIAYAAN', 5], [6, 'PENILAIAN AGUNAN', 5],

            // SKBRK
            // [6, 'PENGEMBANGAN PRODUK DAN PENGELOLAAN PROSES', 5], [5, 'PENGEMBANGAN DAN LAYANAN BISNIS', 5],

            // SKKPP
            // [7, 'PERENCANAAN PERUSAHAAN', 5], [7, 'PORTOFOLIO MANAJEMEN', 5], [7, 'BIDANG KEUANGAN PERUSAHAAN', 3],

            // DIVISI OPERASI
            [9, 'DEPARTEMEN SENTRA OPERASI PERBANKAN', 'DSO', 4], [9, 'DEPARTEMEN ADMINISTRASI PEMBIAYAAN', 'ADP', 4], //[9, 'PENYELAMATAN PEMBIAYAAN', 5]
        );

        for ($i = 0; $i < count($departemen); $i++) {
            Departemen::create([
                'satuan_kerja' => $departemen[$i][0],
                'departemen' => $departemen[$i][1],
                'inisial' => $departemen[$i][2],
                'grup' => $departemen[$i][3]
            ]);
        }

        $cabang = array(
            // Jabodetabek
            ['SELURUH KANTOR CABANG', 11], ['JATINEGARA', 11], ['MANGGA DUA', 11],
            ['SAMANHUDI', 11], ['SUNTER', 11],

            // Non jabodetabek
            ['BANDA ACEH', 12], ['BANDUNG', 12], ['LAMPUNG', 12],
            ['MEDAN', 12], ['PALEMBANG', 12], ['PANAKKUKANG', 12],
            ['SEMARANG', 12], ['SOLO', 12], ['SURABAYA', 12], ['YOGYAKARTA', 12]
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
            // [name, level, satuan kerja, departemen]
            ['klin', 1, null, null, null], ['agusta', 2, 2, null, null], ['hernandi', 6, 2, 2, null],
            ['james', 7, 2, 2, null], ['yudhi', 8, 2, 2, null], ['nur', 12, 2, 2, null],
            ['bayu', 15, 2, 2, null], ['dathu', 6, 2, 3, null], ['riyadi', 7, 2, 3, null],
            ['kertayuga', 8, 2, 3, null], ['eka', 12, 2, 3, null], ['prasetyo', 15, 2, 3, null],
            ['ariefin', 6, 2, 4, null], ['thor', 7, 2, 4, null], ['hatta', 8, 2, 4, null],
            ['febriansyah', 12, 2, 4, null], ['efrinaldi', 15, 2, 4, null], ['alzuhri', 2, 3, null, null],
            ['trinandani', 6, 3, 5, null], ['ragnarok', 7, 3, 5, null], ['githa', 8, 3, 5, null],
            ['refina', 12, 3, 5, null], ['muhammad', 15, 3, 5, null], ['afta', 6, 3, 5, null],
            ['buddin', 7, 3, 5, null], ['komo', 8, 3, 5, null], ['arsyad', 12, 3, 5, null],
            ['risky', 15, 3, 5, null], ['septiawan', 6, 3, 6, null], ['john', 7, 3, 6, null],
            ['lennon', 8, 3, 6, null], ['fredy', 12, 3, 6, null], ['mercury', 15, 3, 6, null],
            ['venus', 2, 4, null, null], ['bumi', 6, 4, 7, null], ['mars', 7, 4, 7, null],
            ['jupiter', 8, 4, 7, null], ['saturn', 12, 4, 7, null], ['uranus', 15, 4, 7, null],
            ['neptunus', 6, 4, 8, null], ['pluto', 7, 4, 8, null], ['alda', 8, 4, 8, null],
            ['tri', 12, 4, 8, null], ['risma', 15, 4, 8, null], ['maharini', 2, 5, null, null],
            ['basuki', 6, 5, 9, null], ['cahaya', 7, 5, 9, null], ['bulan', 8, 5, 9, null],
            ['purnama', 12, 5, 9, null], ['sabit', 15, 5, 9, null], ['padli', 6, 5, 10, null],
            ['tito', 7, 5, 10, null], ['karnavian', 8, 5, 10, null], ['agus', 12, 5, 10, null],
            ['subiyanto', 15, 5, 10, null], ['andi', 6, 8, null, null], ['yoshi', 8, 8, null, null],
            ['noya', 11, 8, null, null], ['samsul', 6, 10, null, null], ['arif', 8, 10, null, null],
            ['ilham', 11, 10, null, null],

            ['burhan', 5, null, null, 1],
            ['jarot', 10, null, null, 2],
            ['adit', 13, null, null, 3],
            ['sopo', 5, null, null, 10],
            ['jarwo', 10, null, null, 11],
            ['bambang', 13, null, null, 12],

        );

        for ($i = 0; $i < count($user); $i++) {
            $data = [
                'name' => $user[$i][0],
                'level' => $user[$i][1],
                'satuan_kerja' => $user[$i][2],
                'departemen' => $user[$i][3],
                'bidang_cabang' => $user[$i][4],
                'password' => hash::make('Syariah@1'),
                'email' => 'yudhinurb@gmail.com'
            ];
            User::create($data);
        }
    }
}
