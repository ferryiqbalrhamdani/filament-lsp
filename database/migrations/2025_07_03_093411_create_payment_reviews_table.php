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
        Schema::create('payment_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->boolean('is_verified')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**2
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reviews');
    }
};
