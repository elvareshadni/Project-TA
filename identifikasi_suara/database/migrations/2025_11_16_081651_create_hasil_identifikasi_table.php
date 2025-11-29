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
            $table->unsignedBigInteger('user_id');   // relasi ke users
            $table->string('file_suara')->nullable(); // nama file audio
            $table->string('hasil')->nullable();     // hasil analisis (sdh, marah, senang, dsb)
            $table->float('akurasi')->nullable();    // confidence score
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_identifikasi');
    }
};
