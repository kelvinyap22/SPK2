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
}