<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hasil_identifikasi', function (Blueprint $table) {
            $table->string('durasi')->nullable()->after('file_suara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hasil_identifikasi', function (Blueprint $table) {
            $table->dropColumn('durasi');
        });
    }
};
