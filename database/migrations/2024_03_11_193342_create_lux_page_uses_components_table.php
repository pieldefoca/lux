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
        Schema::create('lux_page_uses_components', function (Blueprint $table) {
            $table->id();
            $table->string('page_id');
            $table->foreignId('page_component_id')->constrained('lux_page_components', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('lang');
            $table->json('content');
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('lux_pages')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_page_components');
    }
};
