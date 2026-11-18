<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data kriteria dan alternatif yang sudah ada di database
        $kriterias = Kriteria::orderBy('kode')->get();
        $alternatifs = Alternatif::all();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            $this->command->error('Data kriteria atau alternatif kosong! Pastikan KriteriaSeeder dan AlternatifSeeder dijalankan terlebih dahulu.');
            return;
        }

        // Matriks nilai mentah (X) berdasarkan Tabel 8 Jurnal
        // Urutan nilai dalam array merepresentasikan urutan kriteria: [C1, C2, C3, C4, C5]
        $matriksNilai = [
            '16114836' => [86, 3, 3, 8, 1], // Aditya Rahmadi
            '16114840' => [85, 3, 5, 4, 4], // Arya Budiman
            '16114843' => [82, 3, 5, 8, 4], // Cecar Nurcahyadi
            '16114880' => [82, 3, 4, 6, 3], // Yudha Krisnadi
        ];

        foreach ($alternatifs as $alt) {
            if (isset($matriksNilai[$alt->kode_alternatif])) {
                $nilaiList = $matriksNilai[$alt->kode_alternatif];

                foreach ($kriterias as $index => $krit) {
                    Penilaian::create([
                        'alternatif_id' => $alt->id,
                        'kriteria_id' => $krit->id,
                        'nilai' => $nilaiList[$index],
                    ]);
                }
            }
        }
    }
}