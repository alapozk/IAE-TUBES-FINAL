<?php

// app/Models/Assignment.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory;
class Assignment extends Model { use HasFactory;
  protected $fillable=['course_id','title','instructions','due_at','max_score'];
  public function course(){ return $this->belongsTo(Course::class); }
}

