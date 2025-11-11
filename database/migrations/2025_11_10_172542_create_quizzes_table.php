<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel quizzes.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');          // relasi ke courses
            $table->string('title');                          // judul kuis
            $table->integer('duration_minutes')->nullable();  // durasi (opsional)
            $table->integer('total_points')->default(0);      // total skor kuis
            $table->timestamps();

            // optional: relasi ke courses
            // $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }

    /**
     * Rollback migration jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
