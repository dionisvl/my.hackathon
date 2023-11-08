<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CourseTests;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Resources\ModelResource;

class CourseTestsResource extends ModelResource
{
    protected string $model = CourseTests::class;

    protected string $title = 'CourseTests';

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
