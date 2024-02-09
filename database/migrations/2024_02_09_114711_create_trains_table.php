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
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->integer('train_id');
            $table->string('train_name');
            $table->integer('capacity');
            $table->timestamps();
        });

        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->integer('train_id');
            $table->integer('station_id');
            $table->string('arrival_time');
            $table->string('departure_time');
            $table->integer('fare');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
