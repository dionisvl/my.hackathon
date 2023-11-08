<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CourseMaterials;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Resources\ModelResource;

class CourseMaterialsResource extends ModelResource
{
    protected string $model = CourseMaterials::class;
    protected string $title = 'CourseMaterials';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
