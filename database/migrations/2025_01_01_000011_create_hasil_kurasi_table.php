<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kurasi', function (Blueprint $table) {
            $table->increments('id_hasil_kurasi');
            $table->unsignedInteger('id_periode_alternatif')->unique('uk_hasil_kurasi_periode_alternatif');
            $table->decimal('skor_final', 12, 6);
            $table->unsignedInteger('peringkat');
            $table->unsignedInteger('jumlah_gap_negatif')->default(0);
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_periode_alternatif', 'fk_hasil_kurasi_periode_alternatif')
                ->references('id_periode_alternatif')->on('periode_alternatif')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kurasi');
    }
};
