<?php

namespace App\Http\Controllers;

use App\Models\PeriodeKurasi;
use App\Models\PeriodeAlternatif;
use App\Models\Alternatif;
use App\Models\AhpSesi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeKurasiController extends Controller
{
    public function index()
    {
        $periode = PeriodeKurasi::with(['kurator', 'ahpSesi'])->withCount('periodeAlternatif')->latest()->get();
        $kurators = User::where('role', 'kurator')->get();
        $activeAHP = AhpSesi::where('status_aktif', true)->first();

        return view('admin.kurasi.index', compact('periode', 'kurators', 'activeAHP'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_kurasi' => 'required|date',
            'penanggung_jawab' => 'required|string|max:100',
            'id_kurator' => 'nullable|exists:users,id',
            'catatan_umum' => 'nullable|string',
        ]);

        $activeAHP = AhpSesi::where('status_aktif', true)->first();
        if (!$activeAHP) {
            return redirect()->back()->with('error', 'Gagal membuat periode: Tidak ada Sesi AHP yang aktif saat ini.');
        }

        $tanggal = \Carbon\Carbon::parse($request->tanggal_kurasi);

        PeriodeKurasi::create([
            'nama_periode' => $request->nama_periode,
            'tanggal_kurasi' => $request->tanggal_kurasi,
            'bulan' => $tanggal->month,
            'tahun' => $tanggal->year,
            'penanggung_jawab' => $request->penanggung_jawab,
            'id_kurator' => $request->id_kurator,
            'id_ahp_sesi' => $activeAHP->id_ahp_sesi,
            'status_kurasi' => 'belum',
            'catatan_umum' => $request->catatan_umum,
        ]);

        return redirect()->route('admin.kurasi.index')->with('success', 'Periode Kurasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_kurasi' => 'required|date',
            'penanggung_jawab' => 'required|string|max:100',
            'id_kurator' => 'nullable|exists:users,id',
            'catatan_umum' => 'nullable|string',
            'status_kurasi' => 'required|in:belum,berlangsung,selesai',
        ]);

        $tanggal = \Carbon\Carbon::parse($request->tanggal_kurasi);

        $periode = PeriodeKurasi::findOrFail($id);

        // Guard: hanya bisa mengedit saat status 'belum'
        if ($periode->status_kurasi !== 'belum') {
            return redirect()->back()->with('error', 'Periode tidak dapat diedit karena sudah berstatus "' . ucfirst($periode->status_kurasi) . '".');
        }

        $periode->update([
            'nama_periode' => $request->nama_periode,
            'tanggal_kurasi' => $request->tanggal_kurasi,
            'bulan' => $tanggal->month,
            'tahun' => $tanggal->year,
            'penanggung_jawab' => $request->penanggung_jawab,
            'id_kurator' => $request->id_kurator,
            'status_kurasi' => $request->status_kurasi,
            'catatan_umum' => $request->catatan_umum,
        ]);

        return redirect()->route('admin.kurasi.index')->with('success', 'Periode Kurasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periode = PeriodeKurasi::findOrFail($id);
        
        if ($periode->status_kurasi !== 'belum') {
            return redirect()->back()->with('error', 'Hanya periode dengan status "Belum" yang dapat dihapus.');
        }

        $periode->delete();

        return redirect()->route('admin.kurasi.index')->with('success', 'Periode Kurasi berhasil dihapus.');
    }

    public function manageProduk($id)
    {
        $periode = PeriodeKurasi::findOrFail($id);
        
        // Get all alternatifs, we'll indicate in the view which ones are already selected
        $alternatifs = Alternatif::all();
        $selectedAlternatifIds = PeriodeAlternatif::where('id_periode_kurasi', $id)
                                    ->pluck('id_alternatif')
                                    ->toArray();

        return view('admin.kurasi.produk', compact('periode', 'alternatifs', 'selectedAlternatifIds'));
    }

    public function storeProduk(Request $request, $id)
    {
        $periode = PeriodeKurasi::findOrFail($id);
        
        // Guard: hanya bisa mengubah produk saat status 'belum'
        if ($periode->status_kurasi !== 'belum') {
            return redirect()->back()->with('error', 'Produk tidak dapat diubah karena periode sudah berstatus "' . ucfirst($periode->status_kurasi) . '".');
        }

        $request->validate([
            'alternatif_ids' => 'nullable|array',
            'alternatif_ids.*' => 'exists:alternatif,id_alternatif',
        ]);

        $selectedIds = $request->alternatif_ids ?? [];

        DB::transaction(function () use ($periode, $selectedIds) {
            // Delete those not in selectedIds
            PeriodeAlternatif::where('id_periode_kurasi', $periode->id_periode_kurasi)
                ->whereNotIn('id_alternatif', $selectedIds)
                ->delete();

            // Get existing ids to avoid duplicates
            $existingIds = PeriodeAlternatif::where('id_periode_kurasi', $periode->id_periode_kurasi)
                ->pluck('id_alternatif')
                ->toArray();

            $newIds = array_diff($selectedIds, $existingIds);

            // Insert new selections
            $dataToInsert = [];
            foreach ($newIds as $alternatifId) {
                $legalitas = \App\Models\AlternatifLegalitas::where('id_alternatif', $alternatifId)->first();
                
                $dataToInsert[] = [
                    'id_periode_kurasi' => $periode->id_periode_kurasi,
                    'id_alternatif' => $alternatifId,
                    'status_lolos_legalitas' => $legalitas ? $legalitas->lolos_filter : false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($dataToInsert)) {
                PeriodeAlternatif::insert($dataToInsert);
            }
        });

        return redirect()->route('admin.kurasi.produk', $periode->id_periode_kurasi)
                         ->with('success', 'Daftar produk untuk kurasi berhasil diperbarui.');
    }
}
