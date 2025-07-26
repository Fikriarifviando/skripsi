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
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->enum('status_nomor', ['aktif','nonaktif'])->default('aktif');
            $table->string('path_file', 500)->nullable();
            $table->string('nama_file', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn(['status_nomor', 'path_file', 'nama_file']);
        });
    }
};
