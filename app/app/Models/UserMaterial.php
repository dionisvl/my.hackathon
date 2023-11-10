<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property DateTime $viewed_at
 * @property int $user_id
 * @property int $material_id
 */
class UserMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'material_id', 'viewed_at'];

    public static function getStat(): array
    {
        $results = self::select('material_id', 'user_id', 'viewed_at', DB::raw('MAX(id) as max_id'))
            ->groupBy('material_id', 'user_id', 'viewed_at')
            ->get();
        $stat = [];
        foreach ($results as $result) {
            $key = (string)$result->viewed_at;
            if (!isset($stat[$key])) {
                $stat[$key] = 1;
            } else {
                $stat[$key]++;
            }
        }
        return $stat;
    }
}
