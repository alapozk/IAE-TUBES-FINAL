<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['course_id','title','description','file_path','mime','size','extension'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function url(): string {
        return \Storage::disk('public')->url($this->file_path);
    }

    public function isPdf(): bool   { return str_contains($this->mime, 'pdf'); }
    public function isVideo(): bool { return str_starts_with($this->mime, 'video'); }
    public function isPpt(): bool   {
        return in_array($this->extension, ['ppt','pptx','pps','ppsx']);
    }
}
