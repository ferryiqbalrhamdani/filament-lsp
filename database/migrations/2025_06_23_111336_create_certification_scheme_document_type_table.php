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
        Schema::create('certification_scheme_document_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certification_schemes')->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_scheme_document_type');
    }
};
