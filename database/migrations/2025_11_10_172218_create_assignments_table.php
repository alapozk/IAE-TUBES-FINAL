<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel assignments.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->integer('max_score')->default(100);
            $table->timestamps();

            // optional: relasi ke tabel courses
            // $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }

    /**
     * Rollback migration jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
