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
        Schema::table('detections', function (Blueprint $table) {
            $table->dropColumn('initial_power');
            $table->dropColumn('final_power');
            $table->float('avgpower');
            $table->float('kwattora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            //
        });
    }
};
