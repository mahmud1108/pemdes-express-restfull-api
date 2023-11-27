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
        Schema::create('bumdes', function (Blueprint $table) {
            $table->uuid('bumdes_id')->primary()->unique();
            $table->string('bumdes_name');
            $table->string('bumdes_phone');
            $table->string('email');
            $table->string('password');
            $table->string('village_id');
            $table->foreign('village_id')->on('villages')->references('village_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bumdes');
    }
};
