<?php

use App\Models\MoonshineUserRole;

function isAdmin(): bool
{
    return in_array(auth('moonshine')->user()->moonshine_user_role_id, [
        MoonshineUserRole::ADMIN_ROLE_ID,
        MoonshineUserRole::HR_ROLE_ID,
    ], 1);
}
