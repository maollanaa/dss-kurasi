<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria_skala', function (Blueprint $table) {
            $table->increments('id_skala');
            $table->unsignedInteger('id_kriteria');
            $table->unsignedTinyInteger('nilai_skala');
            $table->text('deskripsi_skala');

            $table->unique(['id_kriteria', 'nilai_skala'], 'uk_kriteria_skala');

            $table->foreign('id_kriteria', 'fk_kriteria_skala_kriteria')
                ->references('id_kriteria')->on('kriteria')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria_skala');
    }
};
