<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanTest extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'test_id'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(OnboardingPlan::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
