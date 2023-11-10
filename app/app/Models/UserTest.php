<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTest extends Model
{
    protected $table = 'user_tests';

    use HasFactory;
    public $incrementing = true;

    protected $fillable = ['user_id', 'test_id', 'result', 'completed_at'];

    // порог процента правильных ответов для прохождения теста
    public const PASS_THRESHOLD = 65;

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class);
    }
}
