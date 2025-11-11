<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model {
    use HasFactory;
    protected $fillable = ['title','code','teacher_id','status'];

    public function teacher()  { return $this->belongsTo(User::class,'teacher_id'); }
    public function enrollments() { return $this->hasMany(Enrollment::class,'course_id'); }
}
