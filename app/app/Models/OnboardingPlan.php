<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnboardingPlan extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'title'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(PlanMaterial::class, 'plan_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(PlanTest::class, 'plan_id');
    }
}
