<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_sesi', function (Blueprint $table) {
            $table->increments('id_ahp_sesi');
            $table->string('nama_sesi', 100);
            $table->date('tanggal_sesi');
            $table->decimal('lambda_max', 10, 4)->nullable();
            $table->decimal('ci', 10, 4)->nullable();
            $table->decimal('cr', 10, 4)->nullable();
            $table->boolean('status_aktif')->default(false);
            $table->unsignedBigInteger('dibuat_oleh');
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('dibuat_oleh', 'idx_ahp_sesi_dibuat_oleh');

            $table->foreign('dibuat_oleh', 'fk_ahp_sesi_users')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_sesi');
    }
};
