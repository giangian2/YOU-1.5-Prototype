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
        Schema::create('daily_sensor_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sensor_id');
            $table->float('risparmio_in_giornata');
            $table->float('spreco_in_giornata');
            $table->float('risparmio_per_eccesso');
            $table->float('spreco_per_eccesso');
            $table->foreign('sensor_id')->references('id')->on('sensors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_user_stats');
    }
};
