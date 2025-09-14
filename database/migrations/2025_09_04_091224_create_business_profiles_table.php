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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 255);
            $table->foreignId('art_id')->constrained('arts')->restrictOnDelete();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('website')->nullable();
            $table->string('email')->nullable();
            $table->json('social_links')->nullable();
            $table->enum('privacy_settings', ['public', 'private'])->default('public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
