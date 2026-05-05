<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhpSesi extends Model
{
    protected $table = 'ahp_sesi';
    protected $primaryKey = 'id_ahp_sesi';

    protected $fillable = [
        'nama_sesi',
        'tanggal_sesi',
        'lambda_max',
        'ci',
        'cr',
        'status_aktif',
        'dibuat_oleh'
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }

    public function perbandingan()
    {
        return $this->hasMany(AhpPerbandingan::class, 'id_ahp_sesi', 'id_ahp_sesi');
    }

    public function bobot()
    {
        return $this->hasMany(AhpBobot::class, 'id_ahp_sesi', 'id_ahp_sesi');
    }
}
