<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'course_materials');
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'course_tests');
    }
}
