<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'target_nilai',
        'is_aktif',
        'urutan_tampil'
    ];
}
