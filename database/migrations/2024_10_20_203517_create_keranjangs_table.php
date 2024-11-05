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
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Fixed foreign key definition
            $table->foreignId('lapang_id')->constrained('lapangans')->onDelete('cascade'); // Fixed foreign key definition
            $table->date('tanggal');
            $table->time('waktu_mulai'); // Mengubah timestamp menjadi time
            $table->time('waktu_selesai')->nullable(); // Mengubah timestamp menjadi time
            $table->decimal('total_bayar', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
