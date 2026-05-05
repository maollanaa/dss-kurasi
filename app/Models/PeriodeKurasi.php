<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeKurasi extends Model
{
    protected $table = 'periode_kurasi';
    protected $primaryKey = 'id_periode_kurasi';

    protected $fillable = [
        'nama_periode',
        'tanggal_kurasi',
        'bulan',
        'tahun',
        'id_kurator',
        'penanggung_jawab',
        'status_kurasi',
        'id_ahp_sesi',
        'produk_terbaik_id',
        'catatan_umum',
    ];

    protected $casts = [
        'tanggal_kurasi' => 'date',
    ];

    /**
     * Get the kurator associated with the periode.
     */
    public function kurator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kurator', 'id');
    }

    /**
     * Get the ahp_sesi associated with the periode.
     */
    public function ahpSesi(): BelongsTo
    {
        return $this->belongsTo(AhpSesi::class, 'id_ahp_sesi', 'id_ahp_sesi');
    }

    /**
     * Get the produk terbaik associated with the periode.
     */
    public function produkTerbaik(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'produk_terbaik_id', 'id_alternatif');
    }

    /**
     * Get the periode_alternatif associated with the periode.
     */
    public function periodeAlternatif(): HasMany
    {
        return $this->hasMany(PeriodeAlternatif::class, 'id_periode_kurasi', 'id_periode_kurasi');
    }
}
