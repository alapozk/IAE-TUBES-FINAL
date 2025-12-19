<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDataToMultiDb extends Command
{
    protected $signature = 'db:migrate-multi';
    protected $description = 'Migrate data from main database to multi-database (guru, siswa)';

    public function handle()
    {
        $this->info('Starting data migration...');

        // ===== COPY TO GURU DATABASE =====
        $this->info('Copying to GURU database...');

        // Courses
        $courses = DB::connection('mysql')->table('courses')->get();
        foreach ($courses as $course) {
            DB::connection('guru')->table('courses')->insertOrIgnore((array) $course);
        }
        $this->info("- Courses: {$courses->count()} records");

        // Materials
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('materials')) {
            $materials = DB::connection('mysql')->table('materials')->get();
            foreach ($materials as $m) {
                DB::connection('guru')->table('materials')->insertOrIgnore((array) $m);
            }
            $this->info("- Materials: {$materials->count()} records");
        }

        // Assignments
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('assignments')) {
            $assignments = DB::connection('mysql')->table('assignments')->get();
            foreach ($assignments as $a) {
                DB::connection('guru')->table('assignments')->insertOrIgnore((array) $a);
            }
            $this->info("- Assignments: {$assignments->count()} records");
        }

        // Quizzes
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('quizzes')) {
            $quizzes = DB::connection('mysql')->table('quizzes')->get();
            foreach ($quizzes as $q) {
                DB::connection('guru')->table('quizzes')->insertOrIgnore((array) $q);
            }
            $this->info("- Quizzes: {$quizzes->count()} records");
        }

        // Quiz Questions
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('quiz_questions')) {
            $questions = DB::connection('mysql')->table('quiz_questions')->get();
            foreach ($questions as $q) {
                DB::connection('guru')->table('quiz_questions')->insertOrIgnore((array) $q);
            }
            $this->info("- Quiz Questions: {$questions->count()} records");
        }

        // Quiz Options
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('quiz_options')) {
            $options = DB::connection('mysql')->table('quiz_options')->get();
            foreach ($options as $o) {
                DB::connection('guru')->table('quiz_options')->insertOrIgnore((array) $o);
            }
            $this->info("- Quiz Options: {$options->count()} records");
        }

        // ===== COPY TO SISWA DATABASE =====
        $this->info('Copying to SISWA database...');

        // Enrollments
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('enrollments')) {
            $enrollments = DB::connection('mysql')->table('enrollments')->get();
            foreach ($enrollments as $e) {
                DB::connection('siswa')->table('enrollments')->insertOrIgnore((array) $e);
            }
            $this->info("- Enrollments: {$enrollments->count()} records");
        }

        // Submissions
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('submissions')) {
            $submissions = DB::connection('mysql')->table('submissions')->get();
            foreach ($submissions as $s) {
                DB::connection('siswa')->table('submissions')->insertOrIgnore((array) $s);
            }
            $this->info("- Submissions: {$submissions->count()} records");
        }

        // Quiz Attempts
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('quiz_attempts')) {
            $attempts = DB::connection('mysql')->table('quiz_attempts')->get();
            foreach ($attempts as $a) {
                DB::connection('siswa')->table('quiz_attempts')->insertOrIgnore((array) $a);
            }
            $this->info("- Quiz Attempts: {$attempts->count()} records");
        }

        // Quiz Answers
        if (DB::connection('mysql')->getSchemaBuilder()->hasTable('quiz_answers')) {
            $answers = DB::connection('mysql')->table('quiz_answers')->get();
            foreach ($answers as $a) {
                DB::connection('siswa')->table('quiz_answers')->insertOrIgnore((array) $a);
            }
            $this->info("- Quiz Answers: {$answers->count()} records");
        }

        $this->info('✅ Data migration completed!');
        return 0;
    }
}
