<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class UserTest extends Model
{
    protected $table = 'user_tests';

    use HasFactory;
    public $incrementing = true;

    protected $fillable = ['user_id', 'test_id', 'result', 'completed_at'];

    // порог процента правильных ответов для прохождения теста
    public const PASS_THRESHOLD = 65;

    public static function getStat(): array
    {
        $results = self::select('test_id', 'user_id', 'completed_at', DB::raw('MAX(id) as max_id'))
            ->groupBy('test_id', 'user_id', 'completed_at')
            ->get();
        $stat = [];
        foreach ($results as $result) {
            $key = (string)$result->completed_at;
            if (!isset($stat[$key])) {
                $stat[$key] = 1;
            } else {
                $stat[$key]++;
            }
        }
        return $stat;
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class);
    }
}
