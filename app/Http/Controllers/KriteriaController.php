<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class KriteriaController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $parameterCatalog = collect($this->parameterCatalog());
        $hasJenisParameterColumn = Schema::hasColumn('kriteria', 'jenis_parameter');

        $kriteriaItems = Kriteria::with(['skala' => function ($query) {
            $query->orderBy('nilai_skala');
        }])
            ->orderBy('urutan_tampil')
            ->orderBy('kode_kriteria')
            ->get()
            ->map(function (Kriteria $kriteria) use ($parameterCatalog, $hasJenisParameterColumn) {
                $parameterKey = $this->resolveParameterType($kriteria, $hasJenisParameterColumn);
                $parameter = $parameterCatalog->get($parameterKey, $parameterCatalog->get('subjektif_berskala'));
                $skala = $kriteria->skala->map(function ($item) {
                    return (object) [
                        'nilai_skala' => (int) $item->nilai_skala,
                        'deskripsi_skala' => $item->deskripsi_skala,
                    ];
                })->values();

                return (object) [
                    'id_kriteria' => $kriteria->id_kriteria,
                    'kode_kriteria' => $kriteria->kode_kriteria,
                    'nama_kriteria' => $kriteria->nama_kriteria,
                    'aspek' => $this->formatAspek($kriteria->aspek),
                    'deskripsi_kriteria' => $kriteria->deskripsi_kriteria,
                    'jenis_parameter_key' => $parameterKey,
                    'jenis_parameter' => $parameter['label'],
                    'jenis_parameter_deskripsi' => $parameter['description'],
                    'contoh_parameter' => $parameter['example'],
                    'target_nilai' => (int) $kriteria->target_nilai,
                    'is_aktif' => (bool) $kriteria->is_aktif,
                    'urutan_tampil' => (int) $kriteria->urutan_tampil,
                    'skala' => $skala,
                    'rubrik_lengkap' => $skala->count() >= 5,
                ];
            })
            ->values();

        $parameterGuides = $parameterCatalog->map(function (array $item, string $key) use ($kriteriaItems) {
            $criteriaCount = $kriteriaItems->where('jenis_parameter_key', $key)->count();

            return [
                'key' => $key,
                'icon' => $item['icon'],
                'nama' => $item['label'],
                'contoh' => $item['example'],
                'deskripsi' => $item['description'],
                'rubrik' => $item['rubric'],
                'criteria_count' => $criteriaCount,
            ];
        })->values();

        $pageSummary = [
            'aktif' => $kriteriaItems->where('is_aktif', true)->count(),
            'nonaktif' => $kriteriaItems->where('is_aktif', false)->count(),
            'aspek' => $kriteriaItems->pluck('aspek')->filter()->unique()->count(),
            'target_rata' => round((float) $kriteriaItems->avg('target_nilai'), 1),
            'rubrik_lengkap' => $kriteriaItems->where('rubrik_lengkap', true)->count(),
        ];

        return view('admin.kriteria', compact('kriteriaItems', 'parameterGuides', 'pageSummary'));
    }

    protected function resolveParameterType(Kriteria $kriteria, bool $hasJenisParameterColumn): string
    {
        if ($hasJenisParameterColumn && filled($kriteria->jenis_parameter)) {
            return (string) $kriteria->jenis_parameter;
        }

        $lookupByCode = [
            'C1' => 'subjektif_berskala',
            'C2' => 'range',
            'C3' => 'range',
            'C4' => 'range',
            'C5' => 'ya_tidak',
            'C6' => 'pemenuhan_keadaan',
            'C7' => 'pemenuhan_keadaan',
            'C8' => 'subjektif_berskala',
            'C9' => 'pemenuhan_keadaan',
        ];

        return $lookupByCode[$kriteria->kode_kriteria] ?? 'subjektif_berskala';
    }

    protected function formatAspek(?string $aspek): string
    {
        return match ($aspek) {
            'kualitas_produk' => 'Kualitas Produk',
            'kemasan' => 'Kemasan',
            default => 'Belum Ditentukan',
        };
    }

    protected function parameterCatalog(): array
    {
        return [
            'range' => [
                'label' => 'Range',
                'icon' => 'bi-sliders',
                'example' => 'Harga, kapasitas produksi, masa kedaluwarsa',
                'description' => 'Input berupa rentang nilai numerik yang dikonversi ke skala 1-5 sesuai rubrik penilaian.',
                'rubric' => [
                    'Tetapkan rentang minimum sampai maksimum yang relevan untuk kurasi.',
                    'Gunakan konversi konsisten dari nilai terendah ke nilai paling ideal.',
                    'Pastikan hasil akhir selalu bermuara ke skala 1-5.',
                ],
            ],
            'ya_tidak' => [
                'label' => 'Ya/Tidak',
                'icon' => 'bi-check2-square',
                'example' => 'Kode produksi, atribut wajib pada label',
                'description' => 'Jawaban biner dikonversi menjadi skor skala agar bisa dipakai pada Profile Matching.',
                'rubric' => [
                    'Definisikan kondisi yang dianggap memenuhi sebagai jawaban Ya.',
                    'Dokumentasikan konversi skor, misalnya Ya = 5 dan Tidak = 1.',
                    'Gunakan untuk indikator yang bersifat ada atau tidak ada.',
                ],
            ],
            'pemenuhan_keadaan' => [
                'label' => 'Pemenuhan Keadaan',
                'icon' => 'bi-clipboard2-check',
                'example' => 'Material kemasan, informasi label, uji nutrisi',
                'description' => 'Menilai tingkat pemenuhan kondisi tertentu dari belum memenuhi sampai sangat lengkap.',
                'rubric' => [
                    'Uraikan indikator kondisi yang dicek pada tiap level pemenuhan.',
                    'Bedakan dengan jelas antara memenuhi sebagian, mayoritas, dan seluruh indikator.',
                    'Konversi akhir tetap mengikuti skala 1-5.',
                ],
            ],
            'subjektif_berskala' => [
                'label' => 'Subjektif Berskala',
                'icon' => 'bi-stars',
                'example' => 'Rasa, desain kemasan',
                'description' => 'Kurator memberi penilaian langsung 1-5 berdasarkan panduan deskriptif yang konsisten.',
                'rubric' => [
                    'Sediakan deskriptor kualitas untuk skor rendah, sedang, dan tinggi.',
                    'Gunakan acuan yang sama antar kurator agar hasil penilaian stabil.',
                    'Selaraskan deskripsi skor dengan target ideal yang ditetapkan admin.',
                ],
            ],
        ];
    }
}
