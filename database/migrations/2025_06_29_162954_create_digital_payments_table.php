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
        Schema::create('digital_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('nomor')->nullable();
            $table->boolean('is_nomor')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_payments');
    }
};
