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
        Schema::create('lux_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')
                ->constrained('lux_sliders', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->json('title');
            $table->json('subtitle');
            $table->json('action_text');
            $table->json('action_link');
            $table->unsignedInteger('sort')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_slides');
    }
};
