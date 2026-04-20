<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_alternatif', function (Blueprint $table) {
            $table->increments('id_periode_alternatif');
            $table->unsignedInteger('id_periode_kurasi');
            $table->unsignedInteger('id_alternatif');
            $table->boolean('status_lolos_legalitas')->nullable();
            $table->text('keterangan_filter')->nullable();
            $table->unsignedInteger('urutan_input')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['id_periode_kurasi', 'id_alternatif'], 'uk_periode_alternatif');

            $table->foreign('id_periode_kurasi', 'fk_periode_alternatif_periode')
                ->references('id_periode_kurasi')->on('periode_kurasi')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_alternatif', 'fk_periode_alternatif_alternatif')
                ->references('id_alternatif')->on('alternatif')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_alternatif');
    }
};
