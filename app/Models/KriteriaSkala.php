<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaSkala extends Model
{
    protected $table = 'kriteria_skala';
    public $timestamps = false; // Di-handle oleh database if any

    protected $fillable = [
        'id_kriteria',
        'nilai_skala',
        'deskripsi_skala',
        'is_aktif'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
