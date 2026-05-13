<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            if ($remember) {
                \Illuminate\Support\Facades\Cookie::queue('remember_email', $request->email, 43200); // 30 days
            } else {
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('remember_email'));
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $totalKriteria = \App\Models\Kriteria::count();
            $totalPeriodeKurasi = \App\Models\PeriodeKurasi::count();
            $totalProduk = \App\Models\Alternatif::count();
            
            // Get active AHP session
            $activeSesi = \Illuminate\Support\Facades\DB::table('ahp_sesi')->where('status_aktif', true)->first();
            
            // Get criteria and their latest weights
            $kriteriaBobots = \Illuminate\Support\Facades\DB::table('kriteria')
                ->leftJoin('ahp_bobot', function($join) use ($activeSesi) {
                    $join->on('kriteria.id_kriteria', '=', 'ahp_bobot.id_kriteria');
                    if ($activeSesi) {
                        $join->where('ahp_bobot.id_ahp_sesi', '=', $activeSesi->id_ahp_sesi);
                    } else {
                        // Return null for weights if no session
                        $join->where('ahp_bobot.id_ahp_sesi', '=', -1); 
                    }
                })
                ->orderBy('kriteria.urutan_tampil')
                ->select('kriteria.nama_kriteria', 'ahp_bobot.bobot_prioritas')
                ->get();

            return view('admin.dashboard', compact('totalKriteria', 'totalPeriodeKurasi', 'totalProduk', 'kriteriaBobots'));
        } elseif ($user->role === 'kurator') {
            $userId = Auth::id();
            
            // 1. Get recent active task (berlangsung or belum)
            $recentActiveTask = \App\Models\PeriodeKurasi::where('id_kurator', $userId)
                ->whereIn('status_kurasi', ['berlangsung', 'belum'])
                ->orderBy('created_at', 'desc')
                ->first();
                
            $progress = [
                'assessed' => 0,
                'total' => 0,
                'percentage' => 0
            ];
            
            if ($recentActiveTask) {
                $totalKriteria = \App\Models\Kriteria::count();
                $totalProdukLolos = \App\Models\PeriodeAlternatif::where('id_periode_kurasi', $recentActiveTask->id_periode_kurasi)
                    ->where('status_lolos_legalitas', true)
                    ->count();
                
                $produkDinilai = 0;
                if ($totalProdukLolos > 0 && $totalKriteria > 0) {
                    $paIds = \App\Models\PeriodeAlternatif::where('id_periode_kurasi', $recentActiveTask->id_periode_kurasi)
                        ->where('status_lolos_legalitas', true)
                        ->pluck('id_periode_alternatif');
                        
                    foreach ($paIds as $paId) {
                        $count = \App\Models\PenilaianKurasi::where('id_periode_alternatif', $paId)
                            ->where('dinilai_oleh', $userId)
                            ->count();
                        if ($count >= $totalKriteria) {
                            $produkDinilai++;
                        }
                    }
                }
                
                $progress = [
                    'assessed' => $produkDinilai,
                    'total' => $totalProdukLolos,
                    'percentage' => $totalProdukLolos > 0 ? round(($produkDinilai / $totalProdukLolos) * 100) : 0
                ];
            }
            
            // 2. Stats
            $activeTasksCount = \App\Models\PeriodeKurasi::where('id_kurator', $userId)
                ->whereIn('status_kurasi', ['berlangsung', 'belum'])
                ->count();
                
            $completedTasksCount = \App\Models\PeriodeKurasi::where('id_kurator', $userId)
                ->where('status_kurasi', 'selesai')
                ->count();
                
            $totalProductsCount = \App\Models\PenilaianKurasi::where('dinilai_oleh', $userId)
                ->distinct('id_periode_alternatif')
                ->count();

            return view('kurator.dashboard', compact(
                'recentActiveTask', 
                'progress', 
                'activeTasksCount', 
                'completedTasksCount', 
                'totalProductsCount'
            ));
        }

        // Add error handling if role is not recognized
        Auth::logout();
        return redirect('/')->withErrors(['email' => 'Role pengguna tidak valid.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
