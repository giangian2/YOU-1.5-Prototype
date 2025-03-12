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
        Schema::create('user_presence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_slot_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('present');
            $table->date('day');
            $table->foreign('time_slot_id')->references('id')->on('time_slots');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['day','time_slot_id','user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_presence');
    }
};
