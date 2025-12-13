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
    Schema::create('hasil_identifikasi', function (Blueprint $table) {
        $table->id();
        $table->string('nama')->nullable();
        $table->string('email')->nullable();
        $table->string('gender')->nullable();
        $table->integer('usia')->nullable();
        
        $table->string('sumber')->nullable(); // upload / record
        $table->string('file_suara')->nullable();
        
        $table->string('hasil')->nullable(); // emosi dominan
        $table->string('akurasi')->nullable(); // persentase akurasi
        
        $table->json('distribution_by_emotion')->nullable();
        $table->json('distribution_by_suku')->nullable();
        
        $table->timestamps();
    });
    }

    public function down()
    {
    Schema::dropIfExists('hasil_identifikasi');
    }
};
