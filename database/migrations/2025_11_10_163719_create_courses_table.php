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
        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('code')->unique();
            $t->unsignedBigInteger('teacher_id'); // refer ke users.id (role=teacher)
            $t->enum('status', ['draft','published'])->default('draft');
            $t->timestamps();

            // kalau mau FK ketat, pastikan teacher sudah ada:
            // $t->foreign('teacher_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('courses'); }

};
