<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_kurasi', function (Blueprint $table) {
            $table->increments('id_periode_kurasi');
            $table->string('nama_periode', 100);
            $table->date('tanggal_kurasi');
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedBigInteger('id_kurator');
            $table->string('penanggung_jawab', 100);
            $table->enum('status_kurasi', ['belum', 'berlangsung', 'selesai'])->default('belum');
            $table->unsignedInteger('id_ahp_sesi');
            $table->unsignedInteger('produk_terbaik_id')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_kurator', 'fk_periode_kurasi_kurator')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('id_ahp_sesi', 'fk_periode_kurasi_ahp_sesi')
                ->references('id_ahp_sesi')->on('ahp_sesi')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('produk_terbaik_id', 'fk_periode_kurasi_produk_terbaik')
                ->references('id_alternatif')->on('alternatif')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kurasi');
    }
};
