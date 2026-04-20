<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_perbandingan', function (Blueprint $table) {
            $table->increments('id_ahp_perbandingan');
            $table->unsignedInteger('id_ahp_sesi');
            $table->unsignedInteger('kriteria_1_id');
            $table->unsignedInteger('kriteria_2_id');
            $table->decimal('nilai_perbandingan', 10, 4);

            $table->unique(['id_ahp_sesi', 'kriteria_1_id', 'kriteria_2_id'], 'uk_ahp_perbandingan');

            $table->foreign('id_ahp_sesi', 'fk_ahp_perbandingan_sesi')
                ->references('id_ahp_sesi')->on('ahp_sesi')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('kriteria_1_id', 'fk_ahp_perbandingan_kriteria_1')
                ->references('id_kriteria')->on('kriteria')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('kriteria_2_id', 'fk_ahp_perbandingan_kriteria_2')
                ->references('id_kriteria')->on('kriteria')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_perbandingan');
    }
};
