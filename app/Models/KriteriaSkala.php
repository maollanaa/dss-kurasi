<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KriteriaSkala extends Model
{
    protected $table = 'kriteria_skala';
    protected $primaryKey = 'id_skala';
    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'nilai_skala',
        'deskripsi_skala',
    ];

    protected $casts = [
        'nilai_skala' => 'integer',
    ];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
