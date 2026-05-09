<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\PenilaianKurasi;
use App\Models\PeriodeAlternatif;
use App\Models\PeriodeKurasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianKuratorController extends Controller
{
    /**
     * Menampilkan daftar periode kurasi yang ditugaskan ke kurator login
     */
    public function index()
    {
        $userId = Auth::id();
        
        $periodes = PeriodeKurasi::where('id_kurator', $userId)
            ->orWhereNull('id_kurator')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung progress untuk setiap periode
        foreach ($periodes as $periode) {
            $totalProdukLolos = PeriodeAlternatif::where('id_periode_kurasi', $periode->id_periode_kurasi)
                ->where('status_lolos_legalitas', true)
                ->count();
                
            // Total kriteria aktif
            $totalKriteria = Kriteria::count();
            
            // Berapa produk yang sudah dinilai penuh (jumlah penilaian == total kriteria)
            $produkDinilai = 0;
            
            if ($totalProdukLolos > 0 && $totalKriteria > 0) {
                $periodeAlternatifs = PeriodeAlternatif::where('id_periode_kurasi', $periode->id_periode_kurasi)
                    ->where('status_lolos_legalitas', true)
                    ->get();
                    
                foreach ($periodeAlternatifs as $pa) {
                    $nilaiCount = PenilaianKurasi::where('id_periode_alternatif', $pa->id_periode_alternatif)
                        ->where('dinilai_oleh', $userId)
                        ->count();
                        
                    if ($nilaiCount >= $totalKriteria) {
                        $produkDinilai++;
                    }
                }
            }
            
            $periode->total_produk_lolos = $totalProdukLolos;
            $periode->produk_dinilai = $produkDinilai;
            $periode->progress_percentage = $totalProdukLolos > 0 ? round(($produkDinilai / $totalProdukLolos) * 100) : 0;
        }

        return view('kurator.penilaian.index', compact('periodes'));
    }

    /**
     * Menampilkan daftar produk dalam satu periode kurasi
     */
    public function detailPeriode($id_periode)
    {
        $userId = Auth::id();
        $periode = PeriodeKurasi::findOrFail($id_periode);
        
        // Pastikan kurator hanya mengakses periode miliknya atau yang belum di-assign
        if ($periode->id_kurator != null && $periode->id_kurator != $userId) {
            abort(403, 'Anda tidak memiliki akses ke periode kurasi ini.');
        }

        $totalKriteria = Kriteria::count();

        $produkList = PeriodeAlternatif::with('alternatif')
            ->where('id_periode_kurasi', $id_periode)
            ->orderBy('urutan_input', 'asc')
            ->get();
            
        // Tandai status penilaian untuk masing-masing produk
        foreach ($produkList as $produk) {
            $nilaiCount = PenilaianKurasi::where('id_periode_alternatif', $produk->id_periode_alternatif)
                ->where('dinilai_oleh', $userId)
                ->count();
            
            $produk->is_dinilai = ($totalKriteria > 0 && $nilaiCount >= $totalKriteria);
        }

        return view('kurator.penilaian.detail', compact('periode', 'produkList'));
    }

    /**
     * Menampilkan antarmuka Penilaian (Workspace / Wizard)
     */
    public function workspace($id_periode, $id_alternatif = null)
    {
        $userId = Auth::id();
        $periode = PeriodeKurasi::findOrFail($id_periode);

        if ($periode->id_kurator != null && $periode->id_kurator != $userId) {
            abort(403, 'Anda tidak memiliki akses ke periode kurasi ini.');
        }

        // Jika periode belum dimulai, mulai periode dan assign kurator
        if ($periode->status_kurasi == 'belum') {
            $periode->update([
                'status_kurasi' => 'berlangsung',
                'id_kurator' => $periode->id_kurator ?? $userId
            ]);
        }

        // Ambil daftar produk yang LOLOS legalitas saja untuk panel navigasi (antrean)
        $antreanProduk = PeriodeAlternatif::with('alternatif')
            ->where('id_periode_kurasi', $id_periode)
            ->where('status_lolos_legalitas', true)
            ->orderBy('urutan_input', 'asc')
            ->get();

        if ($antreanProduk->isEmpty()) {
            return redirect()->route('kurator.penilaian.detail', $id_periode)
                ->with('error', 'Belum ada produk yang lolos legalitas untuk dinilai pada periode ini.');
        }

        $totalKriteria = Kriteria::count();

        // Hitung status penilaian untuk panel navigasi
        foreach ($antreanProduk as $p) {
            $nilaiCount = PenilaianKurasi::where('id_periode_alternatif', $p->id_periode_alternatif)
                ->where('dinilai_oleh', $userId)
                ->count();
            $p->is_dinilai = ($totalKriteria > 0 && $nilaiCount >= $totalKriteria);
        }

        // Tentukan produk aktif yang akan dinilai
        $produkAktif = null;
        if ($id_alternatif) {
            $produkAktif = $antreanProduk->firstWhere('id_alternatif', $id_alternatif);
            if (!$produkAktif) {
                // Jika tidak ditemukan atau tidak lolos legalitas
                abort(404, 'Produk tidak ditemukan atau tidak eligible untuk dinilai.');
            }
        } else {
            // Cari produk pertama yang belum dinilai
            $produkAktif = $antreanProduk->firstWhere('is_dinilai', false);
            
            // Jika semua sudah dinilai, tampilkan state "selesai"
            if (!$produkAktif) {
                $semuaDinilai = true;
                $produkAktif = $antreanProduk->first();
                // Tidak redirect, langsung render workspace dengan flag semuaDinilai
            } else {
                // Redirect ke URL dengan ID agar rapi
                return redirect()->route('kurator.penilaian.workspace', [
                    'id_periode' => $id_periode, 
                    'id_alternatif' => $produkAktif->id_alternatif
                ]);
            }
        }

        // Ambil data kriteria beserta skalanya
        // Cek apakah semua produk sudah dinilai
        $semuaDinilai = $semuaDinilai ?? ($antreanProduk->every(fn($item) => $item->is_dinilai));

        $kriteriaList = Kriteria::with(['scales' => function($q) {
            $q->where('is_aktif', true)->orderBy('nilai_skala', 'desc');
        }])->orderBy('urutan_tampil', 'asc')->get();

        // Ambil nilai yang sudah pernah diinput oleh kurator untuk produk aktif
        $penilaianExisting = PenilaianKurasi::where('id_periode_alternatif', $produkAktif->id_periode_alternatif)
            ->where('dinilai_oleh', $userId)
            ->get()
            ->keyBy('id_kriteria');

        return view('kurator.penilaian.workspace', compact(
            'periode', 
            'antreanProduk', 
            'produkAktif', 
            'kriteriaList',
            'penilaianExisting',
            'semuaDinilai'
        ));
    }

    /**
     * Menyimpan nilai untuk satu kriteria tertentu (AJAX)
     */
    public function storePenilaian(Request $request, $id_periode, $id_alternatif, $id_kriteria)
    {
        $request->validate([
            'nilai_input' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();
        
        // Cari id_periode_alternatif
        $periodeAlternatif = PeriodeAlternatif::where('id_periode_kurasi', $id_periode)
            ->where('id_alternatif', $id_alternatif)
            ->firstOrFail();

        // Assign kurator ke periode jika sebelumnya null
        $periode = PeriodeKurasi::find($id_periode);
        if ($periode && $periode->id_kurator == null) {
            $periode->update(['id_kurator' => $userId]);
        }

        // Simpan atau update
        $penilaian = PenilaianKurasi::updateOrCreate(
            [
                'id_periode_alternatif' => $periodeAlternatif->id_periode_alternatif,
                'id_kriteria' => $id_kriteria,
            ],
            [
                'nilai_input' => $request->nilai_input,
                'dinilai_oleh' => $userId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan',
            'data' => $penilaian
        ]);
    }

    /**
     * Menyelesaikan kurasi: update status periode menjadi 'selesai'
     */
    public function selesaikanKurasi($id_periode)
    {
        $userId = Auth::id();
        $periode = PeriodeKurasi::findOrFail($id_periode);

        // Pastikan akses
        if ($periode->id_kurator != null && $periode->id_kurator != $userId) {
            abort(403, 'Anda tidak memiliki akses ke periode kurasi ini.');
        }

        // Update status
        $periode->update([
            'status_kurasi' => 'selesai',
        ]);

        return redirect()->route('kurator.penilaian.selesai', $id_periode);
    }

    /**
     * Halaman informasi bahwa kurasi telah selesai
     */
    public function halamanSelesai($id_periode)
    {
        $userId = Auth::id();
        $periode = PeriodeKurasi::findOrFail($id_periode);

        if ($periode->id_kurator != null && $periode->id_kurator != $userId) {
            abort(403, 'Anda tidak memiliki akses ke periode kurasi ini.');
        }

        // Hitung statistik
        $totalProduk = PeriodeAlternatif::where('id_periode_kurasi', $id_periode)
            ->where('status_lolos_legalitas', true)
            ->count();

        $totalKriteria = Kriteria::count();

        return view('kurator.penilaian.selesai', compact('periode', 'totalProduk', 'totalKriteria'));
    }
}
