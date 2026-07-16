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
        Schema::dropIfExists('socialite');
        Schema::create('socialite', function (Blueprint $table) {
            $table->bigIncrements('idsocialite');
            $table->string('id')->uniqid();
            $table->unsignedBigInteger('iduser')->uniqid();
            $table->string("email")->uniqid();
            $table->string("avatar");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
