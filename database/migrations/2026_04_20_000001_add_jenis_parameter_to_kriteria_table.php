<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->string('jenis_parameter', 30)->nullable()->after('deskripsi_kriteria');
        });

        DB::table('kriteria')
            ->whereIn('kode_kriteria', ['C2', 'C3', 'C4'])
            ->update(['jenis_parameter' => 'range']);

        DB::table('kriteria')
            ->where('kode_kriteria', 'C5')
            ->update(['jenis_parameter' => 'ya_tidak']);

        DB::table('kriteria')
            ->whereIn('kode_kriteria', ['C6', 'C7', 'C9'])
            ->update(['jenis_parameter' => 'pemenuhan_keadaan']);

        DB::table('kriteria')
            ->whereIn('kode_kriteria', ['C1', 'C8'])
            ->update(['jenis_parameter' => 'subjektif_berskala']);
    }

    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->dropColumn('jenis_parameter');
        });
    }
};
