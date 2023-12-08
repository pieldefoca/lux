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
            $table->id();
            $table->boolean('dynamic_page')->default(false);
            $table->string('dynamic_model')->nullable();
            $table->string('name');
            $table->json('slug')->nullable();
            $table->json('slug_prefix')->nullable();
            $table->string('view');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->boolean('is_home_page')->default(false);
            $table->boolean('visible')->default(true);
            $table->timestamps();
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
