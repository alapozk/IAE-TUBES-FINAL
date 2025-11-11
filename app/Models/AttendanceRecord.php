<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model { protected $fillable=['attendance_session_id','student_id','checked_at'];
  public function session(){ return $this->belongsTo(AttendanceSession::class,'attendance_session_id'); }
}
