<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Perhitungan;

class TopsisCalculationController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama (kriteria, alternatif, dan log riwayat).
     */
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode')->get();
        $alternatifs = Alternatif::with('penilaians')->get();
        $riwayats = Perhitungan::latest()->get();

        return view('topsis.index', compact('kriterias', 'alternatifs', 'riwayats'));
    }

    /**
     * Menjalankan proses perhitungan TOPSIS step-by-step dan mencatat lognya.
     */
    public function hitung(Request $request)
    {
        $kriterias = Kriteria::orderBy('kode')->get();
        $alternatifs = Alternatif::with('penilaians')->get();

        if ($alternatifs->count() < 2) {
            return back()->with('error', 'Minimal dibutuhkan 2 alternatif untuk perhitungan TOPSIS.');
        }

        // 1. Matriks Keputusan (X)
        $X = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $penilaian = $alt->penilaians->where('kriteria_id', $krit->id)->first();
                $X[$alt->id][$krit->id] = $penilaian ? $penilaian->nilai : 0;
            }
        }

        // 2. Normalisasi Matriks (R)
        $pembagi = [];
        foreach ($kriterias as $krit) {
            $sumSquares = 0;
            foreach ($alternatifs as $alt) {
                $sumSquares += pow($X[$alt->id][$krit->id], 2);
            }
            $pembagi[$krit->id] = sqrt($sumSquares);
        }

        $R = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $R[$alt->id][$krit->id] = $pembagi[$krit->id] != 0 
                    ? $X[$alt->id][$krit->id] / $pembagi[$krit->id] 
                    : 0;
            }
        }

        // 3. Matriks Ternormalisasi Terbobot (Y)
        $Y = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $Y[$alt->id][$krit->id] = $R[$alt->id][$krit->id] * $krit->bobot;
            }
        }

        // 4. Solusi Ideal Positif & Negatif (A+ / A-)
        $A_plus = [];
        $A_minus = [];
        foreach ($kriterias as $krit) {
            $kolomY = array_column($Y, $krit->id);
            if ($krit->sifat === 'benefit') {
                $A_plus[$krit->id] = max($kolomY);
                $A_minus[$krit->id] = min($kolomY);
            } else {
                $A_plus[$krit->id] = min($kolomY);
                $A_minus[$krit->id] = max($kolomY);
            }
        }

        // 5 & 6. Jarak (D+, D-) & Nilai Preferensi (V)
        $hasilAkhir = [];
        foreach ($alternatifs as $alt) {
            $sumPlus = 0;
            $sumMinus = 0;
            foreach ($kriterias as $krit) {
                $sumPlus += pow($A_plus[$krit->id] - $Y[$alt->id][$krit->id], 2);
                $sumMinus += pow($Y[$alt->id][$krit->id] - $A_minus[$krit->id], 2);
            }
            $D_plus = sqrt($sumPlus);
            $D_minus = sqrt($sumMinus);
            $V = ($D_minus + $D_plus) != 0 ? $D_minus / ($D_minus + $D_plus) : 0;

            $hasilAkhir[] = [
                'alternatif_id' => $alt->id,
                'kode' => $alt->kode_alternatif,
                'nama' => $alt->nama,
                'kelas' => $alt->kelas,
                'jurusan' => $alt->jurusan,
                'd_plus' => round($D_plus, 4),
                'd_minus' => round($D_minus, 4),
                'preferensi' => round($V, 4),
            ];
        }

        // 7. Perangkingan Berdasarkan Nilai Preferensi Terbesar
        usort($hasilAkhir, fn($a, $b) => $b['preferensi'] <=> $a['preferensi']);

        // Simpan rekam jejak perhitungan ke database (Tabel perhitungans)
        $perhitungan = Perhitungan::create([
            'nama_sesi' => $request->nama_sesi ?? 'Seleksi Beasiswa - ' . now()->format('d M Y H:i'),
            'detail_proses' => [
                'kriterias' => $kriterias->toArray(),
                'X' => $X,
                'R' => $R,
                'Y' => $Y,
                'A_plus' => $A_plus,
                'A_minus' => $A_minus,
                'ranking' => $hasilAkhir,
            ]
        ]);

        return redirect()->route('topsis.show', $perhitungan->id)
            ->with('success', 'Perhitungan TOPSIS selesai dan berhasil dicatat!');
    }

    /**
     * Menampilkan detail riwayat proses perhitungan langkah demi langkah.
     */
    public function show($id)
    {
        $perhitungan = Perhitungan::findOrFail($id);
        return view('topsis.show', compact('perhitungan'));
    }

    /**
     * Menghapus log riwayat perhitungan.
     */
    public function destroy($id)
    {
        $perhitungan = Perhitungan::findOrFail($id);
        $namaSesi = $perhitungan->nama_sesi;
        $perhitungan->delete();

        return redirect()->route('topsis.index')->with('success', 'Riwayat perhitungan "' . $namaSesi . '" berhasil dihapus.');
    }
}