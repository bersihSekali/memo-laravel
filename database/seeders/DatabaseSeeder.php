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
            'satuan_kerja' => 'PPO'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'OPR'
        ]);
        SatuanKerja::create([
            'satuan_kerja' => 'PTI'
        ]);

        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'SKTILOG 1'
        ]);
        Departemen::create([
            'satuan_kerja' => '1',
            'departemen' => 'SKTILOG 2'
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
