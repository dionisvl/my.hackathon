<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'material_id'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(OnboardingPlan::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
