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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('job_title', 255);
            $table->foreignId('art_id')->constrained('arts')->restrictOnDelete();
            $table->enum('job_type',['Full Time','Part Time','Contract','Internship'])->index();
            $table->string('location')->nullable();
            $table->date('application_deadline')->nullable();
            $table->text('job_description')->nullable();
            $table->json('required_skills')->nullable();
            $table->decimal('start_budget', 10, 2)->nullable();
            $table->decimal('end_budget', 10, 2)->nullable();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
