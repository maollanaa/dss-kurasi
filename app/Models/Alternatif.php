<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';
    
    // Database handles updated_at via useCurrentOnUpdate, but Laravel expects timestamps by default.
    // Let's keep timestamps enabled but match the migration's behavior if needed.
    public $timestamps = true;

    protected $fillable = [
        'nama_produk',
        'nama_brand_umkm',
        'nama_pemilik',
        'deskripsi_produk',
        'foto_produk',
        'is_aktif',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the legalitas associated with the alternatif.
     */
    public function legalitas()
    {
        return $this->hasOne(AlternatifLegalitas::class, 'id_alternatif', 'id_alternatif');
    }
}
