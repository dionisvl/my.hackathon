<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'active',
        'description',
    ];


    public static function get(string $key): false|string
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            return $setting->active ? $setting->value : false;
        }
        return false;
    }
}
