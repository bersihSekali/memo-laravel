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
            ['Seluruh Satuan Kerja / Divisi', 'ALL', 1], ['Satuan Kerja Teknologi Informasi dan Logistik', 'STL', 1], ['Satuan Kerja Audit Internal', 'SAI', 1],
            ['Satuan Kerja Hukum Dan Sumber Daya Manusia', 'HSD', 1], ['Satuan Kerja Bisnis dan Komunikasi', 'SBK', 1], ['Satuan Kerja Analisa Risiko dan Pembiayaan', 'ARP', 1],
            ['Satuan Kerja Keuangan dan Perencanaan Perusahaan', 'SKA', 1],
            ['Departemen Manajemen Risiko', 'MRK', 5], ['Divisi Operasi', 'DOP', 1],
            ['Departemen Kepatuhan', 'KEP', 5], ['Cabang Jabodetabek', Null, 1], ['Cabang Non Jabodetabek', Null, 1],
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
            [1, 'Seluruh Departemen SKTILOG', 'STL-ALL', 1],
            // Sktilog
            [2, 'Logistik', 'LOG', 4], [2, 'Sistem Prosedur & Pendukung Operasi', 'PPO', 4], [2, 'Departemen Teknologi Informasi', 'DTI', 4],
            // [2, 'sekuriti Teknologi Informasi', 3], [2, 'operasi Teknologi Informasi', 5],

            // Skai
            // [3, 'internal', 3], [3, 'teknologi Informasi', 3], 
            [3, 'Departemen Kantor Pusat & Anti Fraud', 'AKP', 4],
            [3, 'Departemen Audit Kantor Cabang dan Internal Control', 'AKC', 4],

            // Skhsdm
            [4, 'Departemen Sumber Daya Manusia', 'SDM', 4], [4, 'Departemen Hukum', 'DHK', 4],
            // [4, 'budaya Perusahaan Dan Layanan', 5],

            // Sbk
            [5, 'Departemen Komunikasi dan Kesekretariatan Perusahaan', 'KSP', 4], [5, 'Departemen Pendukung Bisnis', 'PDS', 4], [5, 'Departemen Pengembangan Bisnis', 'PBS', 4],
            // [5, 'riset Pemasaran Dan Pendayagunaan Data Warehouse', 5], [5, 'pengembangan Dan Pembinaan Jaringan Cabang', 3],

            // Skarp
            [6, 'Fungsi Pendukung ARP', 'RPD', 6],
            // [6, 'analisa Pembiayaan', 5], [6, 'penilaian Agunan', 5],

            // Skbrk
            // [6, 'pengembangan Produk Dan Pengelolaan Proses', 5], [5, 'pengembangan Dan Layanan Bisnis', 5],

            // Skkpp
            // [7, 'perencanaan Perusahaan', 5], [7, 'portofolio Manajemen', 5], [7, 'bidang Keuangan Perusahaan', 3],

            // Divisi Operasi
            [9, 'Departemen Sentra Operasi Perbankan', 'DSO', 4], [9, 'Departemen Administrasi Pembiayaan', 'ADP', 4], //[9, 'penyelamatan Pembiayaan', 5]
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
            ['Seluruh Kantor Cabang', 11], ['Jatinegara', 11], ['Mangga Dua', 11],
            ['Samanhudi', 11], ['Sunter', 11],

            // Non Jabodetabek
            ['Banda Aceh', 12], ['Bandung', 12], ['Lampung', 12],
            ['Medan', 12], ['Palembang', 12], ['Panakkukang', 12],
            ['Semarang', 12], ['Solo', 12], ['Surabaya', 12], ['Yogyakarta', 12]
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
            // ['Cabang Jatinegara', 2], 
            ['KCP Depok', 2], ['KCP Cileungsi', 2],
            // ['uls Cimanggis', 2],
            // ['uls Margonda Depok', 2], ['uls Bogor', 2], ['uls Gudang Peluru', 2],
            // ['uls Pondok Indah', 2], ['uls Pasar Minggu', 2],

            // Mangga Dua
            // ['Cabang Mangga Dua', 3], 
            ['KCP Ciledug', 3], ['KCP Ciputat', 3],
            // ['uls Tanah Abang', 3], ['uls Bintaro Utama', 3], ['uls Melawai', 3],
            // ['uls Kemang Mansion', 3],

            // Samanhudi
            // ['Cabang Samanhudi', 4], 
            ['KCP Kenari', 4], ['KCP Pasar Anyar Tangerang', 4],
            // ['uls Tangerang', 4], ['uls Pluti Kencana', 4], ['uls Puri Indah', 4],
            // ['uls Bintaro', 4],

            // Sunter
            // ['Cabang Sunter', 5], 
            ['KCP Kelapa Gading', 5], ['KCP Bekasi', 5],
            ['KCP Pasar Kranji Bekasi', 5], ['KCP Pondok Gede', 5], ['KCP Cikarang Selatan', 5],
            // ['uls Juanda Bekasi', 5], ['uls Tanjung Priok', 5],

            // Banda Aceh
            // ['Cabang Banda Aceh', 6], 
            ['KCP Lhokseumawe', 6],
            // , ['uls Bireuen', 6],

            // Bandung
            // ['Cabang Bandung', 7],
            // ['uls Buah Batu', 7], ['uls Kota Baru Parahyangan', 7],
            // ['uls Dago', 7],

            // Lampung
            // ['Cabang Lampung', 8],

            // Medan
            // ['Cabang Medan', 9],
            // ['uls Setiabudi Medan', 9],

            // Palembang
            // ['Cabang Palembang', 10],
            // ['uls Sudirman Palembang', 10], ['uls Ahmad Rivai', 10],

            // Panakkukang
            // ['Cabang Panakkukang', 11],

            // Semarang
            // ['Cabang Semarang', 12],
            // ['uls Pemuda', 12], ['uls Kudus', 12], ['uls Majapahit', 12],

            // Solo
            // ['Cabang Solo', 13],
            // ['uls Slamet Tiyadi', 13], ['uls Singosaren', 13], ['uls Sragen', 13],

            // Surabaya
            // ['Cabang Surabaya', 14], 
            ['KCP Malang', 14], ['KCP Kediri', 14],
            ['KCP Pasuruan', 14], ['KCP Banyuwangi', 14],
            // ['uls Veteran', 14],
            // ['uls Darmo', 14], ['uls Gedangan', 14], ['uls Sepanjang', 14],
            // ['uls Perak Barat', 14], ['uls Sidoarjo', 14], ['uls Pondok Chandra', 14],
            // ['uls Pandaan', 14], ['uls Gresik', 14], ['uls Mojokerto', 14],
            // ['uls Kapas Krampung', 14], ['uls Kepanjen', 14], ['uls Taman Pondok Indah', 14],

            // Yogyakarta
            // ['Cabang Yogyakarta', 15],
            // ['uls Sudirman Yogyakarta', 15]
        );

        for ($i = 0; $i < count($bidang); $i++) {
            BidangCabang::create([
                'bidang' => $bidang[$i][0],
                'cabang_id' => $bidang[$i][1]
            ]);
        }

        $level = array(
            ['admin', 99], ['Kepala Satuan Kerja', 7], ['Kepala Divisi', 7],
            ['Kepala Unit Kerja', 7], ['Kepala Cabang', 6], ['Kepala Departemen', 6],
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
            ['klin', 1, null, null, null, null], ['agusta', 2, 2, null, null, null], ['hernandi', 6, 2, 2, null, null],
            ['james', 7, 2, 2, null, null], ['yudhi', 8, 2, 2, null, null], ['nur', 12, 2, 2, null, null],
            ['bayu', 15, 2, 2, null, null], ['dathu', 6, 2, 3, null, null], ['riyadi', 7, 2, 3, null, null],
            ['kertayuga', 8, 2, 3, null, null], ['eka', 12, 2, 3, null, null], ['prasetyo', 15, 2, 3, null, null],
            ['ariefin', 6, 2, 4, null, null], ['thor', 7, 2, 4, null, null], ['hatta', 8, 2, 4, null, null],
            ['febriansyah', 12, 2, 4, null, null], ['efrinaldi', 15, 2, 4, null, null], ['alzuhri', 2, 3, null, null, null],
            ['trinandani', 6, 3, 5, null, null], ['ragnarok', 7, 3, 5, null, null], ['githa', 8, 3, 5, null, null],
            ['refina', 12, 3, 5, null, null], ['muhammad', 15, 3, 5, null, null], ['afta', 6, 3, 5, null, null],
            ['buddin', 7, 3, 5, null, null], ['komo', 8, 3, 5, null, null], ['arsyad', 12, 3, 5, null, null],
            ['risky', 15, 3, 5, null, null], ['septiawan', 6, 3, 6, null, null], ['john', 7, 3, 6, null, null],
            ['lennon', 8, 3, 6, null, null], ['fredy', 12, 3, 6, null, null], ['mercury', 15, 3, 6, null, null],
            ['venus', 2, 4, null, null, null], ['bumi', 6, 4, 7, null, null], ['mars', 7, 4, 7, null, null],
            ['jupiter', 8, 4, 7, null, null], ['saturn', 12, 4, 7, null, null], ['uranus', 15, 4, 7, null, null],
            ['neptunus', 6, 4, 8, null, null], ['pluto', 7, 4, 8, null, null], ['alda', 8, 4, 8, null, null],
            ['tri', 12, 4, 8, null, null], ['risma', 15, 4, 8, null, null], ['maharini', 2, 5, null, null, null],
            ['basuki', 6, 5, 9, null, null], ['cahaya', 7, 5, 9, null, null], ['bulan', 8, 5, 9, null, null],
            ['purnama', 12, 5, 9, null, null], ['sabit', 15, 5, 9, null, null], ['padli', 6, 5, 10, null, null],
            ['tito', 7, 5, 10, null, null], ['karnavian', 8, 5, 10, null, null], ['agus', 12, 5, 10, null, null],
            ['subiyanto', 15, 5, 10, null, null], ['andi', 6, 8, null, null, null], ['yoshi', 8, 8, null, null, null],
            ['noya', 11, 8, null, null, null], ['samsul', 6, 10, null, null, null], ['arif', 8, 10, null, null, null],
            ['ilham', 11, 10, null, null, null],

            ['burhan', 5, 11, null, 2, null],
            ['jarot', 9, 11, null, 2, null],
            ['adit', 10, 11, null, 2, 2],
            ['denis', 12, 11, null, 2, 2],
            ['sopo', 5, 11, null, 3, null],
            ['jarwo', 9, 11, null, 3, null],
            ['bambang', 10, 11, null, 3, 5],
            ['adel', 12, 11, null, 3, 5],

        );

        for ($i = 0; $i < count($user); $i++) {
            $data = [
                'name' => $user[$i][0],
                'level' => $user[$i][1],
                'satuan_kerja' => $user[$i][2],
                'departemen' => $user[$i][3],
                'cabang' => $user[$i][4],
                'bidang_cabang' => $user[$i][5],
                'password' => hash::make('Syariah@1'),
                'email' => 'yudhinurb@gmail.com'
            ];
            User::create($data);
        }
    }
}
