<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kode_surat_id')->constrained()->cascadeOnDelete();
            $table->string('no_surat_keluar', 255);
            
            $table->text('metadata');//done
            $table->string('gambar_ttd', 255);//done
            $table->string('status', 255);//done
            $table->timestamps();
            
            
            //dihapus di database di file modify
            $table->string('tujuan_surat', 255);
            $table->timestamp('tanggal_surat');
            $table->text('perihal');
            $table->string('sifat', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_keluars');
    }
};
