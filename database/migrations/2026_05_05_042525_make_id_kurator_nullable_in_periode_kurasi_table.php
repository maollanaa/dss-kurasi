<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periode_kurasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kurator')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_kurasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kurator')->nullable(false)->change();
        });
    }
};
