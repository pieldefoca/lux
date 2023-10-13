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
            $table->string('type')->default('general');
			$table->string('phone_1')->nullable();
			$table->string('phone_2')->nullable();
			$table->string('email')->nullable();
			$table->string('google_maps_url')->nullable();
            $table->json('opening_hours');
            $table->json('address_line_1');
            $table->json('address_line_2');
            $table->json('address_line_3');
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
