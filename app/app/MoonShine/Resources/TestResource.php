<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Resources\ModelResource;

class TestResource extends ModelResource
{
    protected string $model = Test::class;

    protected string $title = 'Тесты и экзамены по онбордингу';

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
