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
        Schema::dropIfExists('semester');
        Schema::create('semester', function (Blueprint $table) {
            $table->bigIncrements('idsemester');
            $table->unsignedBigInteger('idinstansi');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('tahunajaran');
            $table->timestamps();
        });
        
        Schema::dropIfExists('semesteraktif');
        Schema::create('semesteraktif', function (Blueprint $table) {
            $table->bigIncrements('idsemesteraktif');
            $table->unsignedBigInteger('idinstansi');
            $table->unsignedBigInteger('idsemester');
            $table->timestamps();
        });

        Schema::dropIfExists('walikelas');
        Schema::create('walikelas', function (Blueprint $table) {
            $table->bigIncrements('idwalikelas');
            $table->unsignedBigInteger('iduser');
            $table->unsignedBigInteger('idsemester');
            $table->unsignedBigInteger('idinstansi');
            $table->unsignedBigInteger('idkelas');
            $table->unsignedBigInteger('idjurusan');
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
