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
        Schema::table('movies', function (Blueprint $table) {
            // Tambahkan kolom media_id
            $table->unsignedBigInteger('media_id')->nullable();  // Tambahkan nullable jika Anda ingin bisa bernilai null

            // Buat foreign key dari media_id ke id di tabel medias dengan tindakan onDelete yang sesuai
            $table->foreign('media_id')->references('id')->on('medias')->onDelete('set null');  // Atau bisa menggunakan 'cascade'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['media_id']);

            // Hapus kolom media_id
            $table->dropColumn('media_id');
        });
    }
};
