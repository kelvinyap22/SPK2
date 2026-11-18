<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
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

    /**
     * Menampilkan form edit alternatif.
     */
    public function edit($id)
    {
        $alternatif = Alternatif::with('penilaians')->findOrFail($id);
        $kriterias = Kriteria::orderBy('kode')->get();

        return view('alternatif.edit', compact('alternatif', 'kriterias'));
    }

    /**
     * Memperbarui data alternatif dan nilainya.
     */
    public function update(Request $request, $id)
    {
        $alt = Alternatif::findOrFail($id);

        $request->validate([
            'kode_alternatif' => 'required|unique:alternatifs,kode_alternatif,' . $alt->id,
            'nama' => 'required',
            'nilai' => 'required|array'
        ]);

        $data = $request->only('kode_alternatif', 'nama');
        if ($request->filled('kelas')) $data['kelas'] = $request->kelas;
        if ($request->filled('jurusan')) $data['jurusan'] = $request->jurusan;
        $alt->update($data);

        foreach ($request->nilai as $kriteriaId => $nilai) {
            Penilaian::updateOrCreate(
                [
                    'alternatif_id' => $alt->id,
                    'kriteria_id' => $kriteriaId
                ],
                [
                    'nilai' => $nilai
                ]
            );
        }

        return redirect()->route('topsis.index')->with('success', 'Data alternatif ' . $alt->nama . ' berhasil diperbarui.');
    }

    /**
     * Menghapus data alternatif beserta nilai kriterianya.
     */
    public function destroy($id)
    {
        $alt = Alternatif::findOrFail($id);
        $nama = $alt->nama;

        $alt->penilaians()->delete();
        $alt->delete();

        return redirect()->route('topsis.index')->with('success', 'Data alternatif ' . $nama . ' berhasil dihapus.');
    }
}