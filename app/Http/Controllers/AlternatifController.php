<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use DB;

class AlternatifController extends Controller
{
    /**
     * Menyimpan data alternatif siswa beserta nilai kriterianya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_alternatif' => 'required|unique:alternatifs',
            'nama' => 'required',
            'nilai' => 'required|array'
        ]);

        $alt = Alternatif::create($request->only('kode_alternatif', 'nama', 'kelas', 'jurusan'));

        foreach ($request->nilai as $kriteriaId => $nilai) {
            Penilaian::create([
                'alternatif_id' => $alt->id,
                'kriteria_id' => $kriteriaId,
                'nilai' => $nilai
            ]);
        }

        return back()->with('success', 'Data alternatif dan nilainya berhasil ditambahkan.');
    }
}