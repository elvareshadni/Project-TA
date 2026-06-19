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
            $table->string('no_hp', 20)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hasil_identifikasi', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });
    }
};
