<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add user_id column to quiz_attempts table (matches existing model structure)
 * Also add selected_answer to quiz_answers for compatibility
 */
return new class extends Migration
{
    protected $connection = 'siswa';

    public function up(): void
    {
        // Add user_id to quiz_attempts (alias for student_id for compatibility)
        Schema::connection($this->connection)->table('quiz_attempts', function (Blueprint $table) {
            if (!Schema::connection($this->connection)->hasColumn('quiz_attempts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('student_id');
                $table->index('user_id');
            }
        });

        // Add selected_answer to quiz_answers for compatibility with existing code
        Schema::connection($this->connection)->table('quiz_answers', function (Blueprint $table) {
            if (!Schema::connection($this->connection)->hasColumn('quiz_answers', 'selected_answer')) {
                $table->string('selected_answer')->nullable()->after('quiz_question_id');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        
        Schema::connection($this->connection)->table('quiz_answers', function (Blueprint $table) {
            $table->dropColumn('selected_answer');
        });
    }
};
