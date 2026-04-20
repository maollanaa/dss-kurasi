<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatif_legalitas', function (Blueprint $table) {
            $table->increments('id_legalitas');
            $table->unsignedInteger('id_alternatif')->unique('uk_alternatif_legalitas_id_alternatif');
            $table->boolean('is_nib')->default(false);
            $table->string('no_nib', 100)->nullable();
            $table->boolean('is_bpom')->default(false);
            $table->string('no_bpom', 100)->nullable();
            $table->boolean('is_sp_pirt')->default(false);
            $table->string('no_sp_pirt', 100)->nullable();
            $table->boolean('is_sertifikat_halal')->default(false);
            $table->string('no_sertifikat_halal', 100)->nullable();
            $table->boolean('lolos_filter')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_alternatif', 'fk_alternatif_legalitas_alternatif')
                ->references('id_alternatif')->on('alternatif')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatif_legalitas');
    }
};
