<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // FK ke courses (guru bisa diambil dari course->teacher_id)
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->longText('instructions')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->enum('submission_mode', ['text', 'file', 'both'])->default('text');
            $table->unsignedInteger('max_points')->nullable();
            $table->enum('status', ['draft','published','closed'])->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
