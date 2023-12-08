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
        Schema::create('lux_contact', function (Blueprint $table) {
            $table->id();
			$table->json('phone_numbers')->nullable();
			$table->json('emails')->nullable();
			$table->json('locations')->nullable();
            $table->json('social_media')->nullable();
            $table->json('timetables')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lux_contact');
    }
};
