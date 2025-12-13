<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setting_waktus', function (Blueprint $table) {
            $table->id();
            // '3-5' atau '9-10'
            $table->string('durasi')->default('3-5');
            $table->timestamps();
        });
    }
};
