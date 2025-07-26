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
        Schema::create('disposisi_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained()->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamp('tgl_disposisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi_surats');
    }
};
