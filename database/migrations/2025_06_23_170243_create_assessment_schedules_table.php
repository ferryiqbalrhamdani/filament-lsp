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
        Schema::create('assessment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_scheme_id')->constrained()->onDelete('cascade');
            $table->foreignId('competency_unit_location_id')->constrained()->onDelete('cascade');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->date('tgl_publish');
            $table->date('tgl_tutup_publish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_schedules');
    }
};
