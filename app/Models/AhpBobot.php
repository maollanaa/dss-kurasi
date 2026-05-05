<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhpBobot extends Model
{
    protected $table = 'ahp_bobot';
    protected $primaryKey = 'id_ahp_bobot';
    public $timestamps = false;

    protected $fillable = [
        'id_ahp_sesi',
        'id_kriteria',
        'bobot_prioritas'
    ];

    public function sesi()
    {
        return $this->belongsTo(AhpSesi::class, 'id_ahp_sesi', 'id_ahp_sesi');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
