<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodeAlternatif extends Model
{
    protected $table = 'periode_alternatif';
    protected $primaryKey = 'id_periode_alternatif';

    protected $fillable = [
        'id_periode_kurasi',
        'id_alternatif',
        'status_lolos_legalitas',
        'keterangan_filter',
        'urutan_input',
    ];

    protected $casts = [
        'status_lolos_legalitas' => 'boolean',
    ];

    /**
     * Get the periode_kurasi that owns the periode_alternatif.
     */
    public function periodeKurasi(): BelongsTo
    {
        return $this->belongsTo(PeriodeKurasi::class, 'id_periode_kurasi', 'id_periode_kurasi');
    }

    /**
     * Get the alternatif that owns the periode_alternatif.
     */
    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif', 'id_alternatif');
    }
}
