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
        Schema::create('lux_mediables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lux_media_id')->constrained('lux_media', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('lux_mediable_id')->bigUnsignedInteger()->nullable();
            $table->string('lux_mediable_type');
            $table->string('collection')->nullable();
            $table->string('locale', 5)->nullable();
            $table->string('key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_mediables');
    }
};
