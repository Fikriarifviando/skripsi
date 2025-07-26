<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            // Mengubah kolom menjadi nullable tanpa menghapus foreign key
            $table->unsignedBigInteger('kode_surat_id')->nullable()->change();

            // Mengubah kolom lain menjadi nullable atau mengatur default
            $table->text('metadata')->nullable()->change();
            $table->string('gambar_ttd', 255)->nullable()->change();
            $table->string('status', 255)->default('draft')->change();
            $table->string('no_surat_keluar', 255)->nullable()->change();

            // Menghapus kolom yang tidak dibutuhkan
            $table->dropColumn(['tujuan_surat', 'tanggal_surat', 'perihal', 'sifat']);
        });
    }

    public function down()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            // Mengembalikan kode_surat_id ke non-nullable
            $table->unsignedBigInteger('kode_surat_id')->nullable(false)->change();

            // Menambahkan kembali kolom yang dihapus (jika rollback)
            $table->string('tujuan_surat', 255);
            $table->date('tanggal_surat');
            $table->string('sifat', 255);
            $table->string('perihal', 255);

            // Mengembalikan metadata dan gambar_ttd menjadi tidak nullable
            $table->text('metadata')->nullable(false)->change();
            $table->string('gambar_ttd', 255)->nullable(false)->change();

            // Menghapus default pada status agar kembali seperti awal
            $table->string('status', 255)->change();
        });
    }
};
