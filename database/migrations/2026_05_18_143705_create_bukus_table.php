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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id('id_buku'); // [cite: 352]
            $table->foreignId('id_kategori')->constrained('kategoris', 'id_kategori')->onDelete('cascade'); // Relasi [cite: 377]
            $table->string('kode_buku')->unique(); // [cite: 106, 352]
            $table->string('judul'); // [cite: 352, 452]
            $table->string('pengarang'); // [cite: 352, 453]
            $table->string('penerbit'); // [cite: 352, 454]
            $table->integer('tahun_terbit'); // [cite: 353, 462]
            $table->integer('jumlah'); // Stok buku [cite: 127, 354]
            $table->string('lokasi_rak'); // [cite: 355, 465]
            $table->string('status')->default('tersedia'); // [cite: 356, 464]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
