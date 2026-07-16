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
        Schema::dropIfExists('akses');
        Schema::create('akses', function (Blueprint $table) {
            $table->bigIncrements('idakses');
            $table->unsignedBigInteger('iduser')->unique();
            $table->enum('akses', ['superadmin','admin', 'pegawai','user', 'waka', 'kepsek']);
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
