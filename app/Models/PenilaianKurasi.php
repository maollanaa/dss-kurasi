<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianKurasi extends Model
{
    protected $table = 'penilaian_kurasi';
    protected $primaryKey = 'id_penilaian';

    protected $fillable = [
        'id_periode_alternatif',
        'id_kriteria',
        'nilai_input',
        'dinilai_oleh',
    ];

    /**
     * Relasi ke PeriodeAlternatif
     */
    public function periodeAlternatif(): BelongsTo
    {
        return $this->belongsTo(PeriodeAlternatif::class, 'id_periode_alternatif', 'id_periode_alternatif');
    }

    /**
     * Relasi ke Kriteria
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }

    /**
     * Relasi ke User (Kurator yang menilai)
     */
    public function kurator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_oleh', 'id');
    }
}
