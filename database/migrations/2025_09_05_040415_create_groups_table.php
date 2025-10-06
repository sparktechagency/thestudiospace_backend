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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('art_id')->constrained('arts')->restrictOnDelete();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->enum('group_type', ['public', 'private']);
            $table->boolean('allow_post')->default(true);
            $table->boolean('admin_approval')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
