<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $connection = 'guru';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'title',
        'course_id',
        'max_attempt',
        'duration',
        'deadline',
        'show_review',
        'is_published',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'is_published' => 'boolean',
        'deadline' => 'datetime',
    ];

    /**
     * Relasi: Quiz milik satu Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relasi: Quiz punya banyak pertanyaan
     * (INI YANG PALING PENTING UNTUK MULTI SOAL)
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Relasi: Quiz punya banyak attempt (jawaban siswa)
     * (opsional tapi sangat direkomendasikan)
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    
}
