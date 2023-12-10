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
        Schema::create('lux_media', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('filename');
            $table->string('disk')->nullable();
            $table->json('alt')->nullable();
            $table->json('title')->nullable();
            $table->string('mime_type');
            $table->string('media_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_media');
    }
};
