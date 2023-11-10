<?php

namespace App\Http\Controllers;

use App\Events\MaterialOpened;
use App\Models\Material;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View;
use Illuminate\Http\RedirectResponse;
use MoonShine\Http\Controllers\MoonShineController;

class MaterialController extends MoonShineController
{
    public function index(): View\Factory|View\View|Application
    {
        $materials = Material::query()
            ->latest()
            ->paginate(10);

        return view('moonshine.materials.index', [
            'materials' => $materials
        ]);
    }

    public function show(Material $material): View\View|\Illuminate\Foundation\Application|View\Factory|Application|RedirectResponse
    {
        // Process the event that the user has opened this model
        event(new MaterialOpened($material));

        return view('moonshine.materials.show', compact('material'));
    }
}
