<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false; // Di-handle oleh database (useCurrent / useCurrentOnUpdate)

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'aspek',
        'deskripsi_kriteria',
        'jenis_parameter',
        'target_nilai',
        'is_aktif',
        'urutan_tampil'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'target_nilai' => 'integer',
        'urutan_tampil' => 'integer',
    ];

    public function skala(): HasMany
    {
        return $this->hasMany(KriteriaSkala::class, 'id_kriteria', 'id_kriteria');
    }
}
