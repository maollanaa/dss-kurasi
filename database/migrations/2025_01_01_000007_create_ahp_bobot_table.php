<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_bobot', function (Blueprint $table) {
            $table->increments('id_ahp_bobot');
            $table->unsignedInteger('id_ahp_sesi');
            $table->unsignedInteger('id_kriteria');
            $table->decimal('bobot_prioritas', 12, 6);

            $table->unique(['id_ahp_sesi', 'id_kriteria'], 'uk_ahp_bobot');

            $table->foreign('id_ahp_sesi', 'fk_ahp_bobot_sesi')
                ->references('id_ahp_sesi')->on('ahp_sesi')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_kriteria', 'fk_ahp_bobot_kriteria')
                ->references('id_kriteria')->on('kriteria')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_bobot');
    }
};
