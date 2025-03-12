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
        Schema::table('daily_sensor_stats', function (Blueprint $table) {
            $table->float('consumo_progressivo_giornaliero');
            $table->float('spreco_giornaliero');
            $table->float('eccesso_giornaliero');
            $table->float('risparmio_giornaliero_da_spreco');
            $table->float('risparmio_giornaliero_da_eccesso');
            $table->dropColumn('risparmio_in_giornata');
            $table->dropColumn('spreco_in_giornata');
            $table->dropColumn(' risparmio_per_eccesso');
            $table->dropColumn(' spreco_per_eccesso');
            $table->dropColumn('consumption');

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
