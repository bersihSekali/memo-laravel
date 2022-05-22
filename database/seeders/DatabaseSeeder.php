<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\SatuanKerja;

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

        SatuanKerja::create([
            'satuan_kerja' => 'SKTILOG'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SKAI'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SKHSDM'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'SBK'
        ]);

        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'LOGISTIK'
        ]);
        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'SISTEM PROSEDUR & PENDUKUNG OPERASI'
        ]);
        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'PENGEMBANGAN TEKNOLOGI INFORMASI'
        ]);
        Departemen::create([
            'satuan_kerja' => '2',
            'departemen' => 'AUDIT INTERNAL'
        ]);
        Departemen::create([
            'satuan_kerja' => '2',
            'departemen' => 'AUDIT TEKNOLOGI INFORMASI'
        ]);
        Departemen::create([
            'satuan_kerja' => '3',
            'departemen' => 'DEPARTEMEN SDM'
        ]);
        Departemen::create([
            'satuan_kerja' => '3',
            'departemen' => 'DEPARTEMEN HUKUM'
        ]);
        Departemen::create([
            'satuan_kerja' => '4',
            'departemen' => 'DEPARTEMEN KOMUNIKASI DAN KESEKTRETARIATAN PERUSAHAAN'
        ]);
        Departemen::create([
            'satuan_kerja' => '4',
            'departemen' => 'DEPARTEMEN PENDUKUNG BISNIS'
        ]);

        User::create([
            'name' => 'klin',
            'level' => 'admin',
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'agusta',
            'level' => 'head',
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'hernandi',
            'level' => 'staff',
            'satuan_kerja' => '1',
            'departemen' => '2',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'yudhi',
            'level' => 'head',
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'nur',
            'level' => 'staff',
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'bayu',
            'level' => 'staff',
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('asdasd')
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '1',
            'departemen_tujuan' => '2',
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asd asdasdasd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '1',
            'departemen_tujuan' => '2',
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asdasdasdasdasdasdasdasd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '1',
            'departemen_tujuan' => '2',
        ]);

        SuratMasuk::create([
            'created_by' => 'hernandi',
            'perihal' => 'asd asdasdasdasd asdasddsa asd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '1',
            'departemen_tujuan' => '1',
        ]);

        SuratMasuk::create([
            'created_by' => 'hernandi',
            'perihal' => 'dasdasdsadsadaasd asd asd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '2',
            'satuan_kerja_tujuan' => '1',
            'departemen_tujuan' => '1',
        ]);
    }
}
