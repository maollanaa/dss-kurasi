<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kurasi_detail', function (Blueprint $table) {
            $table->increments('id_hasil_detail');
            $table->unsignedInteger('id_hasil_kurasi');
            $table->unsignedInteger('id_kriteria');
            $table->unsignedTinyInteger('nilai_input');
            $table->unsignedTinyInteger('target_nilai');
            $table->integer('gap');
            $table->decimal('bobot_gap', 10, 4);
            $table->decimal('bobot_ahp', 12, 6);
            $table->decimal('skor_kriteria_final', 12, 6);
            $table->text('catatan_gap_negatif')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['id_hasil_kurasi', 'id_kriteria'], 'uk_hasil_kurasi_detail');

            $table->foreign('id_hasil_kurasi', 'fk_hasil_kurasi_detail_hasil')
                ->references('id_hasil_kurasi')->on('hasil_kurasi')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_kriteria', 'fk_hasil_kurasi_detail_kriteria')
                ->references('id_kriteria')->on('kriteria')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kurasi_detail');
    }
};
