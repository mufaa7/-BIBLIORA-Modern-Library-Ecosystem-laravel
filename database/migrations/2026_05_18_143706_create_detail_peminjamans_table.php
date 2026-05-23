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
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id('id_detail'); // [cite: 370]
            $table->foreignId('id_peminjaman')->constrained('peminjamans', 'id_peminjaman')->onDelete('cascade'); // [cite: 447, 517]
            $table->foreignId('id_buku')->constrained('bukus', 'id_buku')->onDelete('cascade'); // [cite: 447, 517]
            $table->integer('jumlah'); // [cite: 371, 449]
            $table->string('sub_total')->nullable(); // [cite: 372, 451]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
