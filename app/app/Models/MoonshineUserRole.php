<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $name
 */
class MoonshineUserRole extends \MoonShine\Models\MoonshineUserRole
{
    use HasFactory;

    // Admin
    final public const ADMIN_ROLE_ID = 1;
    // HR-manager
    final public const HR_ROLE_ID = 2;
    // Руководитель
    final public const MANAGER_ROLE_ID = 3;
    // Сотрудник
    final public const WORKER_ROLE_ID = 4;
}
