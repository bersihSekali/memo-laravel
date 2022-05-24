<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Hash;
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
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'agusta',
            'level' => 'sk',
            'satuan_kerja' => '1',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'hernandi',
            'level' => 'dep',
            'satuan_kerja' => '1',
            'departemen' => '1',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'yudhi',
            'level' => 'staff',
            'satuan_kerja' => '2',
            'departemen' => '4',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'nur',
            'level' => 'sk',
            'satuan_kerja' => '2',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'bayu',
            'level' => 'dep',
            'satuan_kerja' => '2',
            'departemen' => '5',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'Eka',
            'level' => 'staff',
            'satuan_kerja' => '3',
            'departemen' => '6',
            'password' => hash::make('asdasd')
        ]);

        // SuratMasuk::create([
        //     'created_by' => 'hernandi',
        //     'perihal' => 'asd asd asd',
        //     'satuan_kerja_asal' => '1',
        //     'departemen_asal' => '1',
        //     'satuan_kerja_tujuan' => '1',
        //     'departemen_tujuan' => '2',
        //     'no_urut' => '1'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'hernandi',
        //     'perihal' => 'asd asd asd asdasdasd',
        //     'satuan_kerja_asal' => '1',
        //     'departemen_asal' => '1',
        //     'satuan_kerja_tujuan' => '1',
        //     'departemen_tujuan' => '3',
        //     'no_urut' => '2'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'hernandi',
        //     'perihal' => 'asd asd asdasdasdasdasdasdasdasd',
        //     'satuan_kerja_asal' => '1',
        //     'departemen_asal' => '1',
        //     'satuan_kerja_tujuan' => '2',
        //     'departemen_tujuan' => '5',
        //     'no_urut' => '3'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'hernandi',
        //     'perihal' => 'asd asdasdasdasd asdasddsa asd',
        //     'satuan_kerja_asal' => '1',
        //     'departemen_asal' => '2',
        //     'satuan_kerja_tujuan' => '1',
        //     'departemen_tujuan' => '1',
        //     'no_urut' => '4'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'hernandi',
        //     'perihal' => 'dasdasdsadsad aasd asd asd',
        //     'satuan_kerja_asal' => '1',
        //     'departemen_asal' => '2',
        //     'satuan_kerja_tujuan' => '1',
        //     'departemen_tujuan' => '1',
        //     'no_urut' => '5'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'yudhi',
        //     'perihal' => 'dasdasdsads adaasd asd asd',
        //     'satuan_kerja_asal' => '2',
        //     'departemen_asal' => '4',
        //     'satuan_kerja_tujuan' => '2',
        //     'departemen_tujuan' => '4',
        //     'no_urut' => '6'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'yudhi',
        //     'perihal' => 'dasdasd sadsadaasd asd asd',
        //     'satuan_kerja_asal' => '2',
        //     'departemen_asal' => '4',
        //     'satuan_kerja_tujuan' => '3',
        //     'departemen_tujuan' => '6',
        //     'no_urut' => '7'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'yudhi',
        //     'perihal' => 'dasda sdsa dsadaasd asd asd',
        //     'satuan_kerja_asal' => '2',
        //     'departemen_asal' => '4',
        //     'satuan_kerja_tujuan' => '3',
        //     'departemen_tujuan' => '6',
        //     'no_urut' => '8'
        // ]);

        // SuratMasuk::create([
        //     'created_by' => 'yudhi',
        //     'perihal' => 'das da sds adsadaasd asd asd',
        //     'satuan_kerja_asal' => '2',
        //     'departemen_asal' => '4',
        //     'satuan_kerja_tujuan' => '4',
        //     'departemen_tujuan' => '8',
        //     'no_urut' => '9'
        // ]);
    }
}
