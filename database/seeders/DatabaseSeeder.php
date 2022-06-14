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
            'Unit Kerja', 'Kantor Kerja', 'Kantor Cabang', 'Departemen',
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
            ['DEPARTEMEN MANAJEMEN RISIKO', 'MRK', 4], ['DIVISI OPERASI', 'DOP', 1],
            ['DEPARTEMEN KEPATUHAN', 'KEP', 4], ['CABANG JABODETABEK', null, 1], ['CABANG NON JABODETABEK', null, 1],
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
            [2, 'LOGISTIK', 'LOG', 3], [2, 'SISTEM PROSEDUR & PENDUKUNG OPERASI', 'PPO', 3], [2, 'DEPARTEMEN TEKNOLOGI INFORMASI', 'DTI', 3],
            // [2, 'SEKURITI TEKNOLOGI INFORMASI', 3], [2, 'OPERASI TEKNOLOGI INFORMASI', 5],

            // SKAI
            // [3, 'INTERNAL', 3], [3, 'TEKNOLOGI INFORMASI', 3], 
            [3, 'DEPARTEMEN KANTOR PUSAT & ANTI FRAUD', 'AKP', 3],
            [3, 'DEPARTEMEN AUDIT KANTOR CABANG DAN INTERNAL CONTROL', 'AKC', 3],

            // SKHSDM
            [4, 'DEPARTEMEN SUMBER DAYA MANUSIA', 'SDM', 3], [4, 'DEPARTEMEN HUKUM', 'DHK', 3],
            // [4, 'BUDAYA PERUSAHAAN DAN LAYANAN', 5],

            // SBK
            [5, 'DEPARTEMEN KOMUNIKASI DAN KESEKRETARIATAN PERUSAHAAN', 'KSP', 3], [5, 'DEPARTEMEN PENDUKUNG BISNIS', 'PDS', 3], [5, 'DEPARTEMEN PENGEMBANGAN BISNIS', 'PBS', 3],
            // [5, 'RISET PEMASARAN DAN PENDAYAGUNAAN DATA WAREHOUSE', 5], [5, 'PENGEMBANGAN DAN PEMBINAAN JARINGAN CABANG', 3],

            // SKARP
            [6, 'FUNGSI PENDUKUNG ARP', 'RPD', 5],
            // [6, 'ANALISA PEMBIAYAAN', 5], [6, 'PENILAIAN AGUNAN', 5],

            // SKBRK
            // [6, 'PENGEMBANGAN PRODUK DAN PENGELOLAAN PROSES', 5], [5, 'PENGEMBANGAN DAN LAYANAN BISNIS', 5],

            // SKKPP
            // [7, 'PERENCANAAN PERUSAHAAN', 5], [7, 'PORTOFOLIO MANAJEMEN', 5], [7, 'BIDANG KEUANGAN PERUSAHAAN', 3],

            // DIVISI OPERASI
            [9, 'DEPARTEMEN SENTRA OPERASI PERBANKAN', 'DSO', 3], [9, 'DEPARTEMEN ADMINISTRASI PEMBIAYAAN', 'ADP', 3], //[9, 'PENYELAMATAN PEMBIAYAAN', 5]
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
            ['klin', 1, null, null], ['agusta', 2, 2, null], ['hernandi', 6, 2, 2],
            ['james', 7, 2, 2], ['yudhi', 8, 2, 2], ['nur', 12, 2, 2],
            ['bayu', 15, 2, 2], ['dathu', 6, 2, 3], ['riyadi', 7, 2, 3],
            ['kertayuga', 8, 2, 3], ['eka', 12, 2, 3], ['prasetyo', 15, 2, 3],
            ['ariefin', 6, 2, 4], ['thor', 7, 2, 4], ['hatta', 8, 2, 4],
            ['febriansyah', 12, 2, 4], ['efrinaldi', 15, 2, 4], ['alzuhri', 2, 3, null],
            ['trinandani', 6, 3, 5], ['ragnarok', 7, 3, 5], ['githa', 8, 3, 5],
            ['refina', 12, 3, 5], ['muhammad', 15, 3, 5], ['afta', 6, 3, 5],
            ['buddin', 7, 3, 5], ['komo', 8, 3, 5], ['arsyad', 12, 3, 5],
            ['risky', 15, 3, 5], ['septiawan', 6, 3, 6], ['john', 7, 3, 6],
            ['lennon', 8, 3, 6], ['fredy', 12, 3, 6], ['mercury', 15, 3, 6],
            ['venus', 2, 4, 7], ['bumi', 6, 4, 7], ['mars', 7, 4, 7],
            ['jupiter', 8, 4, 7], ['saturn', 12, 4, 7], ['uranus', 15, 4, 7],
            ['neptunus', 6, 4, 8], ['pluto', 7, 4, 8], ['alda', 8, 4, 8],
            ['tri', 12, 4, 8], ['risma', 15, 4, 8], ['maharini', 2, 5, null],
            ['basuki', 6, 5, 9], ['cahaya', 7, 5, 9], ['bulan', 8, 5, 9],
            ['purnama', 12, 5, 9], ['sabit', 15, 5, 9], ['padli', 6, 5, 10],
            ['tito', 7, 5, 10], ['karnavian', 8, 5, 10], ['agus', 12, 5, 10],
            ['subiyanto', 15, 5, 10]
        );

        for ($i = 0; $i < count($user); $i++) {
            $data = [
                'name' => $user[$i][0],
                'level' => $user[$i][1],
                'satuan_kerja' => $user[$i][2],
                'departemen' => $user[$i][3],
                'password' => hash::make('Syariah@1')
            ];
            User::create($data);
        }
    }
}
