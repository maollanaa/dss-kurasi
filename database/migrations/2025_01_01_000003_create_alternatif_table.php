<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatif', function (Blueprint $table) {
            $table->increments('id_alternatif');
            $table->string('nama_produk', 150);
            $table->string('nama_brand_umkm', 150);
            $table->text('deskripsi_produk')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};
