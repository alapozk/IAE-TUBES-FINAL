<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for Guru Database
 * Tables: courses, materials, assignments, quizzes, quiz_questions, quiz_options
 * Run with: php artisan migrate --database=guru
 */
return new class extends Migration
{
    protected $connection = 'guru';

    public function up(): void
    {
        // Courses table
        Schema::connection($this->connection)->create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // Reference to main DB users
            $table->string('title');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft'); // draft, active, archived
            $table->timestamps();
            
            $table->index('teacher_id');
            $table->index('status');
        });

        // Materials table
        Schema::connection($this->connection)->create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
        });

        // Assignments table
        Schema::connection($this->connection)->create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->string('submission_mode')->default('file'); // file, text, both
            $table->integer('max_points')->nullable();
            $table->timestamps();
        });

        // Quizzes table
        Schema::connection($this->connection)->create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('max_attempt')->default(1);
            $table->integer('duration')->nullable(); // in minutes
            $table->dateTime('deadline')->nullable();
            $table->boolean('show_review')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        // Quiz Questions table (matches existing structure)
        Schema::connection($this->connection)->create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->string('correct_answer')->nullable();
            $table->timestamps();
        });

        // Quiz Options table (for normalized structure - optional)
        Schema::connection($this->connection)->create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('quiz_options');
        Schema::connection($this->connection)->dropIfExists('quiz_questions');
        Schema::connection($this->connection)->dropIfExists('quizzes');
        Schema::connection($this->connection)->dropIfExists('assignments');
        Schema::connection($this->connection)->dropIfExists('materials');
        Schema::connection($this->connection)->dropIfExists('courses');
    }
};
