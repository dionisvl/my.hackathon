<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterials extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'material_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
