<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class MaterialController extends Controller
{
    public function index(): View\Factory|View\View|Application
    {
        $materials = Material::query()
            ->latest()
            ->paginate(10);

        return view('materials.index', [
            'materials' => $materials
        ]);
    }

    public function show(Material $material): View\View|\Illuminate\Foundation\Application|View\Factory|Application|RedirectResponse
    {
        return view('materials.show', compact('material'));
    }

    public function upload(Request $request)
    {
        $fileName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('uploads', $fileName, 'public');
        return response()->json(['location' => "/storage/$path"]);

        /*$imgpath = request()->file('file')->store('uploads', 'public');
        return response()->json(['location' => "/storage/$imgpath"]);*/

    }
}
