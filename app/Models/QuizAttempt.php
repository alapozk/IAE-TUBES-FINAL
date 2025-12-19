<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $connection = 'siswa';
    
    protected $fillable = ['quiz_id', 'user_id', 'student_id', 'score', 'started_at', 'finished_at', 'status'];
    
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function quiz()
    {
        // Quiz is in guru database - use setConnection
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get user from main database (cross-database relation)
     * Access via $quizAttempt->student
     */
    public function getStudentAttribute()
    {
        return User::find($this->student_id ?? $this->user_id);
    }
    
    /**
     * Alias for student
     */
    public function getUserAttribute()
    {
        return $this->student;
    }
}
