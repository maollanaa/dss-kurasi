<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaSkala;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('scales')->orderBy('urutan_tampil')->get();
        return view('admin.kriteria', compact('kriteria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:100',
            'aspek' => 'required|in:kualitas_produk,kemasan',
            'deskripsi_kriteria' => 'nullable|string',
            'target_nilai' => 'required|integer|between:1,5',
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());

        return redirect()->back()->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function toggleSkala(Request $request)
    {
        $id_kriteria = $request->id_kriteria;
        $nilai_skala = $request->nilai_skala;

        $skala = KriteriaSkala::where('id_kriteria', $id_kriteria)
            ->where('nilai_skala', $nilai_skala)
            ->firstOrFail();

        $skala->is_aktif = !$skala->is_aktif;
        $skala->save();

        return response()->json([
            'success' => true,
            'is_aktif' => $skala->is_aktif,
            'message' => 'Status skala berhasil diubah.'
        ]);
    }
}
