<?php

namespace App\Listeners;

use App\Events\MaterialOpened;
use App\Models\UserMaterial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Логирование события того что материал был открыт пользователем
 */
class LogMaterialOpened
{
    /**
     * Handle the event.
     */
    public function handle(MaterialOpened $event): void
    {
        // Access the model that was opened
        $material = $event->model;
        UserMaterial::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'material_id' => $material->id
            ],
            [
                'viewed_at' => Carbon::now()
            ]
        );

        Log::info("Material with ID {$material->id} was opened. User ID: " . auth()->id());
    }
}
