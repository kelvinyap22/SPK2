<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use DB;

class KriteriaController extends Controller
{
    /**
     * Menampilkan daftar kriteria.
     */
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode')->get();
        return view('kriterias.index', compact('kriterias'));
    }

    /**
     * Menyimpan data kriteria baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kriterias',
            'nama' => 'required',
            'sifat' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric',
        ]);

        Kriteria::create($request->all());

        return back()->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kriteria.
     */
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('kriteria.edit', compact('kriteria'));
    }

    /**
     * Memperbarui data kriteria.
     */
    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:kriterias,kode,' . $kriteria->id,
            'nama' => 'required',
            'sifat' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric',
        ]);

        $kriteria->update($request->only('kode', 'nama', 'sifat', 'bobot'));

        return redirect()->route('topsis.index')->with('success', 'Data kriteria ' . $kriteria->kode . ' berhasil diperbarui.');
    }

    /**
     * Menghapus data kriteria.
     */
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kode = $kriteria->kode;

        $kriteria->penilaians()->delete();
        $kriteria->delete();

        return redirect()->route('topsis.index')->with('success', 'Data kriteria ' . $kode . ' berhasil dihapus.');
    }
}