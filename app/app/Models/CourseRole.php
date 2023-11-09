<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRole extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'role_id'];

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
