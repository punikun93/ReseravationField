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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_trans', 255)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending'); // Possible statuses: pending, completed
            $table->decimal('total_bayar', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
