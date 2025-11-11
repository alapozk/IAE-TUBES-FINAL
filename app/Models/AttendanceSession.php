<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model { protected $fillable=['course_id','session_date','token'];
  public function course(){ return $this->belongsTo(Course::class); }
  public function records(){ return $this->hasMany(AttendanceRecord::class); }
}