<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKomentarToSuratKeluarTable extends Migration
{
    public function up()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->text('komentar')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn('komentar');
        });
    }
}
