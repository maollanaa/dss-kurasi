<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $skala = [
            // Kriteria 1: Rasa
            ['id_kriteria' => 1, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Rasa'],
            ['id_kriteria' => 1, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Rasa'],
            ['id_kriteria' => 1, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Rasa'],
            ['id_kriteria' => 1, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Rasa'],
            ['id_kriteria' => 1, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Rasa'],

            // Kriteria 2: Harga
            ['id_kriteria' => 2, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Harga'],
            ['id_kriteria' => 2, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Harga'],
            ['id_kriteria' => 2, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Harga'],
            ['id_kriteria' => 2, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Harga'],
            ['id_kriteria' => 2, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Harga'],

            // Kriteria 3: Kapasitas produksi
            ['id_kriteria' => 3, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Kapasitas produksi'],
            ['id_kriteria' => 3, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Kapasitas produksi'],
            ['id_kriteria' => 3, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Kapasitas produksi'],
            ['id_kriteria' => 3, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Kapasitas produksi'],
            ['id_kriteria' => 3, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Kapasitas produksi'],

            // Kriteria 4: Masa kadaluwarsa
            ['id_kriteria' => 4, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Masa kadaluwarsa'],
            ['id_kriteria' => 4, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Masa kadaluwarsa'],
            ['id_kriteria' => 4, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Masa kadaluwarsa'],
            ['id_kriteria' => 4, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Masa kadaluwarsa'],
            ['id_kriteria' => 4, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Masa kadaluwarsa'],

            // Kriteria 5: Kode produksi
            ['id_kriteria' => 5, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Kode produksi'],
            ['id_kriteria' => 5, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Kode produksi'],
            ['id_kriteria' => 5, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Kode produksi'],
            ['id_kriteria' => 5, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Kode produksi'],
            ['id_kriteria' => 5, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Kode produksi'],

            // Kriteria 6: Uji nutrisi
            ['id_kriteria' => 6, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Uji nutrisi'],
            ['id_kriteria' => 6, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Uji nutrisi'],
            ['id_kriteria' => 6, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Uji nutrisi'],
            ['id_kriteria' => 6, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Uji nutrisi'],
            ['id_kriteria' => 6, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Uji nutrisi'],

            // Kriteria 7: Material
            ['id_kriteria' => 7, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Material'],
            ['id_kriteria' => 7, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Material'],
            ['id_kriteria' => 7, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Material'],
            ['id_kriteria' => 7, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Material'],
            ['id_kriteria' => 7, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Material'],

            // Kriteria 8: Desain
            ['id_kriteria' => 8, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Desain'],
            ['id_kriteria' => 8, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Desain'],
            ['id_kriteria' => 8, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Desain'],
            ['id_kriteria' => 8, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Desain'],
            ['id_kriteria' => 8, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Desain'],

            // Kriteria 9: Informasi label
            ['id_kriteria' => 9, 'nilai_skala' => 1, 'deskripsi_skala' => 'Deskripsi skala 1 untuk kriteria Informasi label'],
            ['id_kriteria' => 9, 'nilai_skala' => 2, 'deskripsi_skala' => 'Deskripsi skala 2 untuk kriteria Informasi label'],
            ['id_kriteria' => 9, 'nilai_skala' => 3, 'deskripsi_skala' => 'Deskripsi skala 3 untuk kriteria Informasi label'],
            ['id_kriteria' => 9, 'nilai_skala' => 4, 'deskripsi_skala' => 'Deskripsi skala 4 untuk kriteria Informasi label'],
            ['id_kriteria' => 9, 'nilai_skala' => 5, 'deskripsi_skala' => 'Deskripsi skala 5 untuk kriteria Informasi label'],
        ];

        DB::table('kriteria_skala')->insert($skala);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kriteria_skala')->truncate();
    }
};
