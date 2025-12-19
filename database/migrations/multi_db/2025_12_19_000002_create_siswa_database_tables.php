<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for Siswa Database
 * Tables: enrollments, submissions, quiz_attempts, quiz_answers
 * Run with: php artisan migrate --database=siswa
 */
return new class extends Migration
{
    protected $connection = 'siswa';

    public function up(): void
    {
        // Enrollments table (links students to courses)
        Schema::connection($this->connection)->create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // Reference to main DB users
            $table->unsignedBigInteger('course_id');  // Reference to guru DB courses
            $table->string('status')->default('active'); // active, completed, dropped
            $table->timestamps();
            
            $table->unique(['student_id', 'course_id']);
            $table->index('student_id');
            $table->index('course_id');
        });

        // Submissions table (assignment submissions)
        Schema::connection($this->connection)->create('submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');    // Reference to main DB users
            $table->unsignedBigInteger('course_id');     // Reference to guru DB courses
            $table->unsignedBigInteger('assignment_id'); // Reference to guru DB assignments
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('extension')->nullable();
            $table->integer('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('assignment_id');
        });

        // Quiz Attempts table
        Schema::connection($this->connection)->create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // Reference to main DB users
            $table->unsignedBigInteger('quiz_id');    // Reference to guru DB quizzes
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('total_points')->nullable();
            $table->string('status')->default('in_progress'); // in_progress, completed
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('quiz_id');
        });

        // Quiz Answers table
        Schema::connection($this->connection)->create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('quiz_question_id'); // Reference to guru DB
            $table->unsignedBigInteger('selected_option_id')->nullable(); // Reference to guru DB
            $table->text('text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points_earned')->default(0);
            $table->timestamps();
            
            $table->index('quiz_question_id');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('quiz_answers');
        Schema::connection($this->connection)->dropIfExists('quiz_attempts');
        Schema::connection($this->connection)->dropIfExists('submissions');
        Schema::connection($this->connection)->dropIfExists('enrollments');
    }
};
