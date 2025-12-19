<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model {
    use HasFactory;
    
    protected $connection = 'siswa';
    
    protected $fillable = ['course_id','student_id','status'];

    public function course()  { return $this->belongsTo(Course::class,'course_id'); }
    public function student() { return $this->belongsTo(User::class,'student_id'); }
}
