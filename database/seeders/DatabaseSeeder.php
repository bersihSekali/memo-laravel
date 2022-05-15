<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'satuan_kerja' => '2',
            'departemen' => '2',
            'password' => hash::make('asdasd')
        ]);

        User::create([
            'name' => 'hernandi',
            'level' => 'staff',
            'satuan_kerja' => '3',
            'departemen' => '3',
            'password' => hash::make('asdasd')
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '2',
            'departemen_tujuan' => '2',
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asd asdasdasd',
            'satuan_kerja_asal' => '2',
            'departemen_asal' => '1',
            'satuan_kerja_tujuan' => '3',
            'departemen_tujuan' => '2',
        ]);

        SuratMasuk::create([
            'created_by' => 'agusta',
            'perihal' => 'asd asd asdasdasdasdasdasdasdasd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '2',
            'satuan_kerja_tujuan' => '2',
            'departemen_tujuan' => '3',
        ]);

        SuratMasuk::create([
            'created_by' => 'hernandi',
            'perihal' => 'asd asdasdasdasd asdasddsa asd',
            'satuan_kerja_asal' => '1',
            'departemen_asal' => '3',
            'satuan_kerja_tujuan' => '3',
            'departemen_tujuan' => '1',
        ]);

        SuratMasuk::create([
            'created_by' => 'hernandi',
            'perihal' => 'dasdasdsadsadaasd asd asd',
            'satuan_kerja_asal' => '2',
            'departemen_asal' => '2',
            'satuan_kerja_tujuan' => '2',
            'departemen_tujuan' => '1',
        ]);
    }
}
