<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cover_picture')->nullable();
            $table->string('job_title')->nullable();
            $table->string('comapny_name')->nullable();
            $table->string('location')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->text('bio')->nullable();
            $table->enum('profile_visibility', ['Public', 'Connected', 'Private'])
                  ->default('Public')
                  ->index();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
