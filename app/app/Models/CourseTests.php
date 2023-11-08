<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTests extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'test_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
