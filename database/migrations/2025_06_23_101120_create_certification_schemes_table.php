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
        Schema::create('certification_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_skema');
            $table->string('judul_skema');
            $table->string('jenis_skema');
            $table->text('deskripsi_skema');
            $table->string('tujuan_skema');
            $table->string('kode_referensi');
            $table->string('tahun_terbit');
            $table->string('lembaga_penyelenggara');
            $table->float('harga');
            $table->boolean('is_active_skema')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_schemes');
    }
};
