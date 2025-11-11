<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('enrollments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('course_id');
            $t->unsignedBigInteger('student_id'); // users.id (role=student)
            $t->enum('status',['pending','enrolled','rejected'])->default('enrolled');
            $t->timestamps();
            $t->unique(['course_id','student_id']);

            // FK opsional:
            // $t->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            // $t->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('enrollments'); }

};
