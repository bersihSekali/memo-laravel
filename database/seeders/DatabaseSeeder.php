<?php

namespace Database\Seeders;

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

        SatuanKerja::create([
            'satuan_kerja' => 'SKTILOG',
            'grup' => '1'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SKAI',
            'grup' => '1'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SKHSDM',
            'grup' => '1'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SBK',
            'grup' => '1'
        ]);

        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'LOGISTIK',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'SISTEM PROSEDUR & PENDUKUNG OPERASI',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'PENGEMBANGAN TEKNOLOGI INFORMASI',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '2',
            'departemen' => 'AUDIT INTERNAL',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '2',
            'departemen' => 'AUDIT TEKNOLOGI INFORMASI',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '3',
            'departemen' => 'DEPARTEMEN SDM',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '3',
            'departemen' => 'DEPARTEMEN HUKUM',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '4',
            'departemen' => 'DEPARTEMEN KOMUNIKASI DAN KESEKTRETARIATAN PERUSAHAAN',
            'grup' => '3'
        ]);

        Departemen::create([
            'satuan_kerja' => '4',
            'departemen' => 'DEPARTEMEN PENDUKUNG BISNIS',
            'grup' => '3'
        ]);

        Departemen::create([
            'departemen' => 'Kantor Cabang Jatinegara',
            'grup' => '2'
        ]);

        Departemen::create([
            'departemen' => 'Kantor Cabang Surabaya',
            'grup' => '2'
        ]);

        Departemen::create([
            'departemen' => 'Manajemen Risiko',
            'grup' => '4'
        ]);

        Departemen::create([
            'departemen' => 'Kepatuhan',
            'grup' => '4'
        ]);

        Level::create([
            'jabatan' => 'Admin',
            'golongan' => 99
        ]);

        Level::create([
            'jabatan' => 'Kepala Satuan Kerja',
            'golongan' => 7
        ]);

        Level::create([
            'jabatan' => 'Kepala Divisi',
            'golongan' => 7
        ]);

        Level::create([
            'jabatan' => 'Kepala Unit Kerja',
            'golongan' => 7
        ]);

        Level::create([
            'jabatan' => 'Kepala Cabang',
            'golongan' => 6
        ]);

        Level::create([
            'jabatan' => 'Kepala Departemen',
            'golongan' => 6
        ]);

        Level::create([
            'jabatan' => 'Senior Officer',
            'golongan' => 6
        ]);

        Level::create([
            'jabatan' => 'Kepala Bidang',
            'golongan' => 5
        ]);

        Level::create([
            'jabatan' => 'Kepala Operasi Cabang',
            'golongan' => 5
        ]);

        Level::create([
            'jabatan' => 'Kepala Cabang Pembantu',
            'golongan' => 5
        ]);

        Level::create([
            'jabatan' => 'Officer',
            'golongan' => 5
        ]);

        Level::create([
            'jabatan' => 'Kepala Bagian',
            'golongan' => 4
        ]);

        Level::create([
            'jabatan' => 'Kepala ULS A',
            'golongan' => 4
        ]);

        Level::create([
            'jabatan' => 'Associate Officer',
            'golongan' => 4
        ]);

        Level::create([
            'jabatan' => 'Staff',
            'golongan' => 3
        ]);

        User::create([
            'name' => 'klin',
            'level' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'agusta',
            'level' => '2', // Kepala Satuan Kerja
            'satuan_kerja' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'hernandi',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'james',
            'level' => '7', // Senior officer
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'yudhi',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'nur',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'bayu',
            'level' => '15', // Staff
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'dathu',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'riyadi',
            'level' => '7', // Senior officer
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'kertayuga',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'eka',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'prasetyo',
            'level' => '15', // Staff
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'ariefin',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '1',
            'departemen' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'thor',
            'level' => '7', // Senior officer
            'satuan_kerja' => '1',
            'departemen' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'hatta',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '1',
            'departemen' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'febriansyah',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '1',
            'departemen' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'efrinaldi',
            'level' => '15', // Staff
            'satuan_kerja' => '1',
            'departemen' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'alzuhri',
            'level' => '2', // Kepala Satuan Kerja
            'satuan_kerja' => '2',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'trinandadi',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'ragnarok',
            'level' => '7', // Senior officer
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'githa',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'refina',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'muhammad',
            'level' => '15',
            'satuan_kerja' => '2', // Staff
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'afta',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'komo',
            'level' => '7', // Senior officer
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'buddin',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'arsyad',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'risky',
            'level' => '15', // Staff
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'septiawan',
            'level' => '2', // Kepala Satuan Kerja
            'satuan_kerja' => '3',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'john',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'bulan',
            'level' => '7', // Senior officer
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'lennon',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'alda',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'risma',
            'level' => '15', // Staff
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'freddy',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '3',
            'departemen' => '7',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'matahari',
            'level' => '7', // Senior officer
            'satuan_kerja' => '3',
            'departemen' => '7',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'mercury',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '3',
            'departemen' => '7',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'venus',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '3',
            'departemen' => '7',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'bumi',
            'level' => '15', // Staff
            'satuan_kerja' => '3',
            'departemen' => '7',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'mars',
            'level' => '2', // Kepala Satuan Kerja
            'satuan_kerja' => '4',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'jupiter',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '4',
            'departemen' => '8',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'bintang',
            'level' => '7', // Senior officer
            'satuan_kerja' => '4',
            'departemen' => '8',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'saturnus',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '4',
            'departemen' => '8',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'uranus',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '4',
            'departemen' => '8',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'neptunus',
            'level' => '15', // Staff
            'satuan_kerja' => '4',
            'departemen' => '8',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'pluto',
            'level' => '6', // Kepala Departemen
            'satuan_kerja' => '4',
            'departemen' => '9',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'axio',
            'level' => '7', // Senior officer
            'satuan_kerja' => '4',
            'departemen' => '9',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'fadli',
            'level' => '8', // Kepala Bidang
            'satuan_kerja' => '4',
            'departemen' => '9',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'zon',
            'level' => '12', // Kepala Bagian
            'satuan_kerja' => '4',
            'departemen' => '9',
            'password' => hash::make('Syariah@1')
        ]);

        User::create([
            'name' => 'puan',
            'level' => '15', // Staff
            'satuan_kerja' => '4',
            'departemen' => '9',
            'password' => hash::make('Syariah@1')
        ]);
    }
}
