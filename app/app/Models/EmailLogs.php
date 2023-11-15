<?php

declare(strict_types=1);

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $email
 */
class EmailLogs extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'type'];

    public static function logEmail(int $userId, string $email, string $type): self
    {
        return self::create([
            'user_id' => $userId,
            'email' => $email,
            'type' => $type,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class);
    }
}
