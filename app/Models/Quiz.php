<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Quiz.php
class Quiz extends Model { protected $fillable=['course_id','title','duration_minutes','total_points'];
  public function course(){ return $this->belongsTo(Course::class); }
}

