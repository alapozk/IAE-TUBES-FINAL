<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * Database connection for this model
     * Uses 'guru' database for multi-database architecture
     */
    protected $connection = 'guru';

    protected $fillable = [
        'title',
        'code',
        'teacher_id',
        'status'
    ];

    // ===== RELATIONS =====

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * 🔑 RELASI PENTING UNTUK VALIDASI ENROLMENT
     * FK di tabel enrollments adalah 'student_id', bukan 'user_id'
     */
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',   // pivot table
            'course_id',     // FK ke courses
            'student_id'     // FK ke users (FIXED: was 'user_id')
        );
    }

    public function materials()
    {
        return $this->hasMany(Material::class)->latest();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
