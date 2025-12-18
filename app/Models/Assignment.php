<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'instructions',
        'due_at',
        'submission_mode',
        'max_points',
    ];

    protected $casts = [
        'due_at' => 'datetime',   // penting biar format() jalan
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
