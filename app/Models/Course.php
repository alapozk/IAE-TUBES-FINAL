<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

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
     */
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',   // pivot table
            'course_id',
            'user_id'
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
