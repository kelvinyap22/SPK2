<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = [
            [
                'kode' => 'C1', 
                'nama' => 'Nilai Rata-Rata', 
                'sifat' => 'benefit', 
                'bobot' => 0.4624
            ],
            [
                'kode' => 'C2', 
                'nama' => 'Penghasilan Orang Tua', 
                'sifat' => 'cost', 
                'bobot' => 0.1951
            ],
            [
                'kode' => 'C3', 
                'nama' => 'Tanggungan Orang Tua', 
                'sifat' => 'benefit', 
                'bobot' => 0.1951
            ],
            [
                'kode' => 'C4', 
                'nama' => 'Jarak Tempat Tinggal', 
                'sifat' => 'benefit', 
                'bobot' => 0.0736
            ],
            [
                'kode' => 'C5', 
                'nama' => 'Kehadiran', 
                'sifat' => 'benefit', 
                'bobot' => 0.0736
            ],
        ];

        foreach ($kriterias as $k) {
            Kriteria::create($k);
        }
    }
}