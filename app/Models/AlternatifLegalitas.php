<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlternatifLegalitas extends Model
{
    protected $table = 'alternatif_legalitas';
    protected $primaryKey = 'id_legalitas';
    public $timestamps = false; // updated_at is handled by DB

    protected $fillable = [
        'id_alternatif',
        'is_nib',
        'no_nib',
        'is_bpom',
        'no_bpom',
        'is_sp_pirt',
        'no_sp_pirt',
        'is_sertifikat_halal',
        'no_sertifikat_halal',
        'lolos_filter',
        'keterangan',
        'updated_at'
    ];

    /**
     * Get the alternatif that owns the legalitas.
     */
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif', 'id_alternatif');
    }
}
