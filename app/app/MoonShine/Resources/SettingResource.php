<?php

namespace App\MoonShine\Resources;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class SettingResource extends ModelResource
{
    protected string $model = Setting::class;

    protected string $title = 'Настройки приложения';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Ключ', 'key'),
                Text::make('Значение', 'value'),
                Text::make('Описание', 'description'),
                Switcher::make('Активно', 'active'),
            ])
        ];
    }


    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return [];
    }
}
