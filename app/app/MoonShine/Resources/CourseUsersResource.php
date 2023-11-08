<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CourseUsers;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Resources\ModelResource;

class CourseUsersResource extends ModelResource
{
    protected string $model = CourseUsers::class;

    protected string $title = 'CourseUsers';

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
