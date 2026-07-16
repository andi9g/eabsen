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
        Schema::dropIfExists('registrasilink');
        Schema::create('registrasilink', function (Blueprint $table) {
            $table->bigIncrements('idregistrasilink');
            $table->unsignedBigInteger('idinstansi')->unique();
            $table->enum('akses', ["pegawai", "user"]);
            $table->string('kode');
            $table->boolean('status');
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
