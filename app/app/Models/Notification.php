<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'message'];

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }
}
