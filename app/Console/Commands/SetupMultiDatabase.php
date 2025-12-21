<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SetupMultiDatabase extends Command
{
    protected $signature = 'db:setup-multi';
    protected $description = 'Setup all tables for multi-database architecture (guru, siswa)';

    public function handle()
    {
        $this->info('🚀 Setting up Multi-Database Tables...');
        
        // ========================================
        // GURU DATABASE (courses, materials, assignments, quizzes)
        // ========================================
        $this->info('');
        $this->info('📚 Setting up GURU database...');
        
        // Drop existing tables if exist
        Schema::connection('guru')->dropIfExists('quiz_options');
        Schema::connection('guru')->dropIfExists('quiz_questions');
        Schema::connection('guru')->dropIfExists('quizzes');
        Schema::connection('guru')->dropIfExists('assignments');
        Schema::connection('guru')->dropIfExists('materials');
        Schema::connection('guru')->dropIfExists('courses');
        
        // Create courses table
        Schema::connection('guru')->create('courses', function ($table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('title');
            $table->string('code')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });
        $this->info('  ✅ courses table created');

        // Create materials table
        Schema::connection('guru')->create('materials', function ($table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
        });
        $this->info('  ✅ materials table created');

        // Create assignments table
        Schema::connection('guru')->create('assignments', function ($table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->string('submission_mode')->default('both');
            $table->integer('max_points')->nullable();
            $table->timestamps();
        });
        $this->info('  ✅ assignments table created');

        // Create quizzes table
        Schema::connection('guru')->create('quizzes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('max_attempt')->nullable();
            $table->integer('duration')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->boolean('show_review')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        $this->info('  ✅ quizzes table created');

        // Create quiz_questions table
        Schema::connection('guru')->create('quiz_questions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('question');
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->string('correct_answer')->nullable();
            $table->timestamps();
        });
        $this->info('  ✅ quiz_questions table created');

        // Create quiz_options table
        Schema::connection('guru')->create('quiz_options', function ($table) {
            $table->id();
            $table->unsignedBigInteger('quiz_question_id');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
        $this->info('  ✅ quiz_options table created');

        // ========================================
        // SISWA DATABASE (enrollments, submissions, quiz_attempts, quiz_answers)
        // ========================================
        $this->info('');
        $this->info('👨‍🎓 Setting up SISWA database...');
        
        // Drop existing tables if exist
        Schema::connection('siswa')->dropIfExists('quiz_answers');
        Schema::connection('siswa')->dropIfExists('quiz_attempts');
        Schema::connection('siswa')->dropIfExists('submissions');
        Schema::connection('siswa')->dropIfExists('enrollments');

        // Create enrollments table
        Schema::connection('siswa')->create('enrollments', function ($table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');
            $table->string('status')->default('active');
            $table->timestamps();
        });
        $this->info('  ✅ enrollments table created');

        // Create submissions table
        Schema::connection('siswa')->create('submissions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
        });
        $this->info('  ✅ submissions table created');

        // Create quiz_attempts table
        Schema::connection('siswa')->create('quiz_attempts', function ($table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->string('status')->default('in_progress');
            $table->timestamps();
        });
        $this->info('  ✅ quiz_attempts table created');

        // Create quiz_answers table
        Schema::connection('siswa')->create('quiz_answers', function ($table) {
            $table->id();
            $table->unsignedBigInteger('quiz_attempt_id');
            $table->unsignedBigInteger('quiz_question_id');
            $table->unsignedBigInteger('quiz_option_id')->nullable();
            $table->string('selected_answer')->nullable();
            $table->timestamps();
        });
        $this->info('  ✅ quiz_answers table created');

        $this->info('');
        $this->info('🎉 All multi-database tables created successfully!');
        $this->info('');
        $this->info('Database Summary:');
        $this->info('  GURU: courses, materials, assignments, quizzes, quiz_questions, quiz_options');
        $this->info('  SISWA: enrollments, submissions, quiz_attempts, quiz_answers');
        
        return 0;
    }
}
