<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\EmailLogs;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class EmailLogsResource extends ModelResource
{
    protected string $model = EmailLogs::class;

    protected string $title = 'Лог отправки уведомлений';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),

            Block::make([
                Text::make('Тема', 'type')
                    ->readonly(),
            ]),

            Block::make([
                Text::make('email')->readonly(),
            ]),

            Block::make([
                BelongsTo::make('Пользователь', 'user', resource: new MoonShineUserResource())
                    ->readonly(),
            ]),

            Block::make([
                Text::make('Отправлено', 'created_at')->readonly(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
