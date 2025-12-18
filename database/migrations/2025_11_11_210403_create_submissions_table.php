<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('submissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $t->string('title');
            $t->string('file_path');
            $t->string('mime', 100);
            $t->unsignedBigInteger('size');
            $t->string('extension', 20)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('submissions');
    }
};
