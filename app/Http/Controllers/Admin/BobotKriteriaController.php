<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\AhpSesi;
use App\Models\AhpPerbandingan;
use App\Models\AhpBobot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BobotKriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('id_kriteria')->get();
        
        // Dapatkan sesi AHP aktif
        $activeSesi = AhpSesi::with(['perbandingan', 'bobot'])->where('status_aktif', true)->first();
        
        $perbandinganData = [];
        if ($activeSesi && $activeSesi->perbandingan->isNotEmpty()) {
            foreach ($activeSesi->perbandingan as $p) {
                $perbandinganData[$p->kriteria_1_id][$p->kriteria_2_id] = $p->nilai_perbandingan;
            }
        }

        // Siapkan list pasangan untuk form
        $pairs = [];
        $n = count($kriterias);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $k1 = $kriterias[$i];
                $k2 = $kriterias[$j];
                
                // Cari nilai default jika ada
                $val = 1; // default 'Sama penting'
                if (isset($perbandinganData[$k1->id_kriteria][$k2->id_kriteria])) {
                    $val = $perbandinganData[$k1->id_kriteria][$k2->id_kriteria];
                } elseif (isset($perbandinganData[$k2->id_kriteria][$k1->id_kriteria])) {
                    // inverse
                    $val = 1 / $perbandinganData[$k2->id_kriteria][$k1->id_kriteria];
                }

                // Format val untuk dropdown (mendekati nilai fraksi string)
                $fractionStr = '1';
                if (abs($val - 1) < 0.0001) $fractionStr = '1';
                elseif (abs($val - 3) < 0.0001) $fractionStr = '3';
                elseif (abs($val - 5) < 0.0001) $fractionStr = '5';
                elseif (abs($val - 7) < 0.0001) $fractionStr = '7';
                elseif (abs($val - 9) < 0.0001) $fractionStr = '9';
                elseif (abs($val - (1/3)) < 0.0001) $fractionStr = '1/3';
                elseif (abs($val - (1/5)) < 0.0001) $fractionStr = '1/5';
                elseif (abs($val - (1/7)) < 0.0001) $fractionStr = '1/7';
                elseif (abs($val - (1/9)) < 0.0001) $fractionStr = '1/9';
                else $fractionStr = '1'; // fallback

                $pairs[] = [
                    'k1' => $k1,
                    'k2' => $k2,
                    'value' => $fractionStr
                ];
            }
        }

        // Siapkan hasil perhitungan jika ada sesi aktif
        $hasilAhp = null;
        if ($activeSesi) {
            $hasilAhp = [
                'sesi' => $activeSesi,
                'matrix' => [],
                'normalized' => [],
                'bobot' => [],
                'colSum' => [],
            ];
            
            // Rekonstruksi matriks 9x9 (atau NxN)
            foreach ($kriterias as $k1) {
                $hasilAhp['matrix'][$k1->id_kriteria] = [];
                $colSum = 0;
                foreach ($kriterias as $k2) {
                    if ($k1->id_kriteria == $k2->id_kriteria) {
                        $val = 1.0;
                    } else {
                        if (isset($perbandinganData[$k1->id_kriteria][$k2->id_kriteria])) {
                            $val = $perbandinganData[$k1->id_kriteria][$k2->id_kriteria];
                        } elseif (isset($perbandinganData[$k2->id_kriteria][$k1->id_kriteria])) {
                            $val = 1.0 / $perbandinganData[$k2->id_kriteria][$k1->id_kriteria];
                        } else {
                            $val = 1.0;
                        }
                    }
                    $hasilAhp['matrix'][$k1->id_kriteria][$k2->id_kriteria] = $val;
                }
            }
            
            // Hitung col sum
            foreach ($kriterias as $k2) {
                $sum = 0;
                foreach ($kriterias as $k1) {
                    $sum += $hasilAhp['matrix'][$k1->id_kriteria][$k2->id_kriteria];
                }
                $hasilAhp['colSum'][$k2->id_kriteria] = $sum;
            }
            
            // Hitung bobot dan normalisasi
            if ($activeSesi->bobot->isNotEmpty()) {
                foreach ($kriterias as $k1) {
                    foreach ($kriterias as $k2) {
                        $hasilAhp['normalized'][$k1->id_kriteria][$k2->id_kriteria] = 
                            $hasilAhp['matrix'][$k1->id_kriteria][$k2->id_kriteria] / $hasilAhp['colSum'][$k2->id_kriteria];
                    }
                    $b = $activeSesi->bobot->where('id_kriteria', $k1->id_kriteria)->first();
                    $hasilAhp['bobot'][$k1->id_kriteria] = $b ? $b->bobot_prioritas : 0;
                }
            } else {
                // Bobot tidak ada karena CR > 0.1, hitung ulang untuk ditampilkan
                foreach ($kriterias as $k1) {
                    $rowSum = 0;
                    foreach ($kriterias as $k2) {
                        $normVal = $hasilAhp['matrix'][$k1->id_kriteria][$k2->id_kriteria] / $hasilAhp['colSum'][$k2->id_kriteria];
                        $hasilAhp['normalized'][$k1->id_kriteria][$k2->id_kriteria] = $normVal;
                        $rowSum += $normVal;
                    }
                    $hasilAhp['bobot'][$k1->id_kriteria] = $rowSum / $n;
                }
            }
        }

        return view('admin.bobot', compact('kriterias', 'pairs', 'hasilAhp', 'activeSesi'));
    }

    public function calculate(Request $request)
    {
        $kriterias = Kriteria::orderBy('id_kriteria')->get();
        $n = count($kriterias);
        
        if ($n < 2) {
            return redirect()->back()->with('error', 'Kriteria minimal harus ada 2.');
        }

        $inputPairs = $request->input('pair', []); // format: pair[k1_id][k2_id] = "3" atau "1/3"

        DB::beginTransaction();
        try {
            // Cari atau buat sesi aktif
            $activeSesi = AhpSesi::where('status_aktif', true)->first();
            if (!$activeSesi) {
                $activeSesi = AhpSesi::create([
                    'nama_sesi' => 'Penilaian Bobot ' . date('Y-m-d H:i'),
                    'tanggal_sesi' => date('Y-m-d'),
                    'status_aktif' => true,
                    'dibuat_oleh' => Auth::id() ?? 1 // Fallback if no auth
                ]);
            } else {
                $activeSesi->update([
                    'nama_sesi' => 'Penilaian Bobot ' . date('Y-m-d H:i'),
                    'tanggal_sesi' => date('Y-m-d'),
                    'dibuat_oleh' => Auth::id() ?? 1
                ]);
                // Hapus data lama
                AhpPerbandingan::where('id_ahp_sesi', $activeSesi->id_ahp_sesi)->delete();
                AhpBobot::where('id_ahp_sesi', $activeSesi->id_ahp_sesi)->delete();
            }

            $matrix = [];
            
            // Inisialisasi struktur array matrix terlebih dahulu agar tidak menimpa reverse-entry
            foreach ($kriterias as $k) {
                $matrix[$k->id_kriteria] = [];
            }
            
            // Build the matrix & save inputs
            foreach ($kriterias as $i => $k1) {
                foreach ($kriterias as $j => $k2) {
                    if ($i == $j) {
                        $matrix[$k1->id_kriteria][$k2->id_kriteria] = 1.0;
                    } elseif ($i < $j) {
                        $valStr = isset($inputPairs[$k1->id_kriteria][$k2->id_kriteria]) ? $inputPairs[$k1->id_kriteria][$k2->id_kriteria] : '1';
                        if (strpos($valStr, '/') !== false) {
                            $parts = explode('/', $valStr);
                            $val = (float)$parts[0] / (float)$parts[1];
                        } else {
                            $val = (float)$valStr;
                        }
                        $matrix[$k1->id_kriteria][$k2->id_kriteria] = $val;
                        $matrix[$k2->id_kriteria][$k1->id_kriteria] = 1.0 / $val;

                        // Save Perbandingan
                        AhpPerbandingan::create([
                            'id_ahp_sesi' => $activeSesi->id_ahp_sesi,
                            'kriteria_1_id' => $k1->id_kriteria,
                            'kriteria_2_id' => $k2->id_kriteria,
                            'nilai_perbandingan' => $val
                        ]);
                    }
                }
            }

            // Normalisasi & Bobot Prioritas
            $colSum = [];
            foreach ($kriterias as $k2) {
                $sum = 0;
                foreach ($kriterias as $k1) {
                    $sum += $matrix[$k1->id_kriteria][$k2->id_kriteria];
                }
                $colSum[$k2->id_kriteria] = $sum;
            }

            $bobot = [];
            foreach ($kriterias as $k1) {
                $rowSum = 0;
                foreach ($kriterias as $k2) {
                    $norm = $matrix[$k1->id_kriteria][$k2->id_kriteria] / $colSum[$k2->id_kriteria];
                    $rowSum += $norm;
                }
                $bobot[$k1->id_kriteria] = $rowSum / $n;
            }

            // Konsistensi (Lambda Max, CI, CR)
            $lambdaMax = 0;
            foreach ($kriterias as $k1) {
                $lambdaMax += $colSum[$k1->id_kriteria] * $bobot[$k1->id_kriteria];
            }

            $ci = ($lambdaMax - $n) / ($n - 1);
            
            // RI values for n=1 to 15
            $riArray = [0, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57];
            $ri = $riArray[$n] ?? 1.45; // default 1.45 for n=9 if array fails
            
            $cr = $ri == 0 ? 0 : $ci / $ri;

            // Update Sesi
            $activeSesi->update([
                'lambda_max' => $lambdaMax,
                'ci' => $ci,
                'cr' => $cr
            ]);

            // Save bobot ONLY IF CR <= 0.1
            if ($cr <= 0.1) {
                foreach ($kriterias as $k1) {
                    AhpBobot::create([
                        'id_ahp_sesi' => $activeSesi->id_ahp_sesi,
                        'id_kriteria' => $k1->id_kriteria,
                        'bobot_prioritas' => $bobot[$k1->id_kriteria]
                    ]);
                }
                DB::commit();
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Bobot AHP berhasil dihitung dan disimpan secara permanen. Nilai CR konsisten ('.number_format($cr, 3).').'
                    ]);
                }
                return redirect()->route('admin.bobot.index')->with('success', 'Bobot AHP berhasil dihitung dan disimpan secara permanen. Nilai CR konsisten ('.number_format($cr, 3).').');
            } else {
                DB::commit(); // Tetap commit untuk menyimpan form perbandingan agar user tak mengulang
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Peringatan: Nilai Consistency Ratio (CR) tidak konsisten ('.number_format($cr, 3).'). Batas maksimal adalah 0.1. Bobot TIDAK TERSIMPAN, silakan sesuaikan ulang perbandingan kriteria.'
                    ]);
                }
                return redirect()->route('admin.bobot.index')->with('error', 'Peringatan: Nilai Consistency Ratio (CR) tidak konsisten ('.number_format($cr, 3).'). Batas maksimal adalah 0.1. Bobot TIDAK TERSIMPAN, silakan sesuaikan ulang perbandingan kriteria.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
