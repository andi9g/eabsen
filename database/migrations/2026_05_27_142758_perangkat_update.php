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
        Schema::table('perangkat', function (Blueprint $table) {
            $table->string('target')->after('fungsiperangkat')->nullable();
            $table->enum('action', ["timer", "none"])->after('target')->default("timer");
            $table->string('uuid')->after('action')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perangkat', function (Blueprint $table) {
            $table->dropColumn('target');
            $table->dropColumn('action');
            $table->dropColumn('uuid');
        });
    }
};
