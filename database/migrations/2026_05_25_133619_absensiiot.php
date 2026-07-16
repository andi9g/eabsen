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
        Schema::dropIfExists('detailuser');
        Schema::create('detailuser', function (Blueprint $table) {
            $table->bigIncrements('iddetailuser');
            $table->unsignedBigInteger('iduser');
            $table->unsignedBigInteger('idinstansi');
            $table->char('nip', 20)->nullable();
            $table->string("alamat")->nullable();
            $table->timestamps();
        });
        
        Schema::dropIfExists('posisi');
        // Schema::create('posisi', function (Blueprint $table) {
        //     $table->bigIncrements('idposisi');
        //     $table->unsignedBigInteger('iduser');
        //     $table->enum('namaposisi', ['superadmin', 'admin', 'pegawai']);
        //     $table->timestamps();
        // });
Schema::dropIfExists('detinstansiailuser');
        Schema::create('instansi', function (Blueprint $table) {
            $table->bigIncrements('idinstansi');
            $table->string('namainstansi', 100);
            $table->string('npsn', 100)->unique();
            $table->string('kota', 100)->nullable();
            $table->string('alamat', 100)->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
Schema::dropIfExists('siswa');
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigIncrements('idsiswa');
            $table->unsignedBigInteger('idkelas');
            $table->unsignedBigInteger('idjurusan');
            $table->unsignedBigInteger('idinstansi');
            $table->char('nisn', 20)->unique();
            $table->char('nis', 20);
            $table->string('namasiswa', 100);
            $table->enum('jk', ['L', 'P']);
            $table->string('alamat', 100);
            $table->string('hp', 100);
            $table->timestamps();
        });
Schema::dropIfExists('kelas');
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('idkelas');
            $table->unsignedBigInteger('idinstansi');
            $table->string('namakelas', 10);
            $table->timestamps();
        });
Schema::dropIfExists('jurusan');
        Schema::create('jurusan', function (Blueprint $table) {
            $table->bigIncrements('idjurusan');
            $table->unsignedBigInteger('idinstansi');
            $table->string('namajurusan', 100)->nullable();
            $table->string('inisialjurusan', 100);
            $table->timestamps();
        });
Schema::dropIfExists('kartu');
        Schema::create('kartu', function (Blueprint $table) {
            $table->bigIncrements('idkartu');
            $table->unsignedBigInteger('idsiswa');
            $table->unsignedBigInteger('idinstansi');
            $table->char('uuid', 20);
            $table->timestamps();
        });
Schema::dropIfExists('perangkat');
        Schema::create('perangkat', function (Blueprint $table) {
            $table->bigIncrements('idperangkat');
            $table->unsignedBigInteger('idinstansi');
            $table->string('kodeperangkat')->unique();
            $table->enum('fungsiperangkat', ['absen', 'register']);
            $table->timestamps();
        });
Schema::dropIfExists('absensiswa');
        Schema::create('absensiswa', function (Blueprint $table) {
            $table->bigIncrements('idabsensiswa');
            $table->unsignedBigInteger('idinstansi');
            $table->unsignedBigInteger('idsiswa');
            $table->dateTime('waktumasuk');
            $table->dateTime('waktukeluar')->nullable();
            $table->enum('status', ['a', 'h', 'i', 's']);
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
