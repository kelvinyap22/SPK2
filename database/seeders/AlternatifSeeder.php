<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        // Data sampel siswa / alternatif calon penerima beasiswa (Berdasarkan Jurnal)
        $alternatifs = [
            [
                'kode_alternatif' => '16114836', 
                'nama' => 'Aditya Rahmadi',
                'kelas' => 'XI',
                'jurusan' => 'RPL'
            ],
            [
                'kode_alternatif' => '16114840', 
                'nama' => 'Arya Budiman',
                'kelas' => 'XI',
                'jurusan' => 'RPL'
            ],
            [
                'kode_alternatif' => '16114843', 
                'nama' => 'Cecar Nurcahyadi',
                'kelas' => 'XI',
                'jurusan' => 'RPL'
            ],
            [
                'kode_alternatif' => '16114880', 
                'nama' => 'Yudha Krisnadi',
                'kelas' => 'XI',
                'jurusan' => 'RPL'
            ],
        ];

        foreach ($alternatifs as $alt) {
            Alternatif::create($alt);
        }
    }
}