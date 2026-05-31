<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('hari');
        Schema::dropIfExists('harilibur');
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->bigIncrements('idpengaturan');
            $table->unsignedBigInteger('idinstansi');
            $table->time('jammasuk')->default('08:00:00');
            $table->time('jampulang')->default('13:00:00');
            $table->timestamps();
        });

        DB::table('pengaturan')->insert([
            'idinstansi' => 1,
            'jammasuk' => '08:00:00',
            'jampulang' => '13:00:00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Carbon::setLocale('id');
        $tanggalawal = Carbon::now()->startOfWeek()->format('Y-m-d');
        $tanggalakhir = Carbon::now()->endOfWeek()->format('Y-m-d');

        $tanggal = CarbonPeriod::create($tanggalawal, $tanggalakhir)->toArray();
        $hari = [];
        
        foreach ($tanggal as $tgl) {
            $hari[] = Carbon::parse($tgl)->isoFormat('dddd');
        }
        // dd($hari);
        
        Schema::create('hari', function (Blueprint $table) use ($hari) {
            $table->bigIncrements('idhari');
            $table->enum('namahari', $hari)->unique();
            $table->timestamps();
        });
        
        foreach ($hari as $h) {
            DB::table('hari')->insert([
                'namahari' => $h,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Schema::create('harilibur', function (Blueprint $table) {
            $table->bigIncrements('idharilibur');
            $table->unsignedBigInteger('idinstansi');
            $table->unsignedBigInteger('idhari');
            $table->date('tanggal')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
