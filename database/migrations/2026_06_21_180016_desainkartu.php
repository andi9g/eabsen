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
        Schema::dropIfExists('desainkartu');
        Schema::create('desainkartu', function (Blueprint $table) {
            $table->bigIncrements('iddesainkartu');
            $table->unsignedBigInteger('idinstansi');
            $table->timestamps();
        });
        Schema::dropIfExists('detaildesainkartu');
        Schema::create('detaildesainkartu', function (Blueprint $table) {
            $table->bigIncrements('iddetaildesainkartu');
            $table->unsignedBigInteger('iddesainkartu');
            $table->enum('desainkartu', ["solid", "gambar"])->default('solid');
            $table->string('gambardepan')->nullable();
            $table->string('gambarbelakang')->nullable();
            $table->string('warnadepan')->nullable();
            $table->string('warnabelakang')->nullable();
            $table->string('warnatextdepan')->nullable();
            $table->string('warnatextbelakang')->nullable();
            $table->string('warnaborder')->nullable();
            $table->string('tebalborder')->nullable();
            $table->string('radiusborder')->nullable();
            $table->timestamps();
        });
     Schema::dropIfExists('deskripsikartu');
        Schema::create('deskripsikartu', function (Blueprint $table) {
            $table->bigIncrements('iddeskripsikartu');
            $table->unsignedBigInteger('iddesainkartu');
            $table->string('judul');
            $table->string('deskripsi');
            $table->timestamps();
        });
         Schema::dropIfExists('datadesainkartu');
        Schema::create('datadesainkartu', function (Blueprint $table) {
            $table->bigIncrements('datadesainkartu');
            $table->unsignedBigInteger('iddesainkartu');
            $table->enum("identitas", ["jurusan", "agama", "instansi", "alamat", "kelamin", "ttl"]);
            $table->integer("index");
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
