<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add option_a, option_b, option_c, option_d, correct_answer columns to quiz_questions
 * Run with: php artisan migrate --database=guru --path=database/migrations/multi_db/...
 */
return new class extends Migration
{
    protected $connection = 'guru';

    public function up(): void
    {
        Schema::connection($this->connection)->table('quiz_questions', function (Blueprint $table) {
            if (!Schema::connection($this->connection)->hasColumn('quiz_questions', 'option_a')) {
                $table->string('option_a')->nullable()->after('question');
            }
            if (!Schema::connection($this->connection)->hasColumn('quiz_questions', 'option_b')) {
                $table->string('option_b')->nullable()->after('option_a');
            }
            if (!Schema::connection($this->connection)->hasColumn('quiz_questions', 'option_c')) {
                $table->string('option_c')->nullable()->after('option_b');
            }
            if (!Schema::connection($this->connection)->hasColumn('quiz_questions', 'option_d')) {
                $table->string('option_d')->nullable()->after('option_c');
            }
            if (!Schema::connection($this->connection)->hasColumn('quiz_questions', 'correct_answer')) {
                $table->string('correct_answer')->nullable()->after('option_d');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['option_a', 'option_b', 'option_c', 'option_d', 'correct_answer']);
        });
    }
};
