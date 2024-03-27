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
        Schema::create('lux_pages', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->json('slug')->nullable();
            $table->string('controller')->nullable();
            $table->string('controller_action')->nullable();
            $table->string('livewire_component')->nullable();
            $table->boolean('visible')->default(true);
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_pages');
    }
};
