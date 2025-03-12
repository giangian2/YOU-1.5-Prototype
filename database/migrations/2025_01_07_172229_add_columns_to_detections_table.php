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
            $table->dropColumn('avgpower');
            $table->dropColumn('kwattora');
            $table->float('apower1');
            $table->float('apower2');
            $table->float('aenergy1');
            $table->float('aenergy2');
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
