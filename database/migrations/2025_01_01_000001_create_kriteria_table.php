<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->increments('id_kriteria');
            $table->string('kode_kriteria', 20)->unique('uk_kriteria_kode');
            $table->string('nama_kriteria', 100);
            $table->enum('aspek', ['kualitas_produk', 'kemasan']);
            $table->text('deskripsi_kriteria')->nullable();
            $table->unsignedTinyInteger('target_nilai');
            $table->boolean('is_aktif')->default(true);
            $table->unsignedInteger('urutan_tampil')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
