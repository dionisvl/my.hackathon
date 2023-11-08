<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;
    public $incrementing = true;

    protected $fillable = ['user_id', 'test_id', 'result', 'completed_at'];
}
