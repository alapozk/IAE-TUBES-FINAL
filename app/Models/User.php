<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // opsional bila pakai token API
use App\Models\Enrollment; // atau nama model enroll kamu
use App\Models\Course;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = ['name','email','password','role','status'];
    protected $hidden = ['password','remember_token'];

    public function enrollments()
    {
        // sesuaikan nama model + kolom foreign key di tabel enroll
        return $this->hasMany(Enrollment::class, 'student_id');
        // atau 'user_id' kalau pakai itu
    }

    // Langsung ambil courses yang diikuti
    public function enrolledCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments',   // nama tabel pivot
            'student_id',    // FK ke users
            'course_id'      // FK ke courses
        );
    }
}
