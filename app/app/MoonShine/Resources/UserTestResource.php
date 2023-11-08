<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\UserTest;
use App\MoonShine\Pages\UserTest\UserTestDetailPage;
use App\MoonShine\Pages\UserTest\UserTestFormPage;
use App\MoonShine\Pages\UserTest\UserTestIndexPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class UserTestResource extends ModelResource
{
    protected string $model = UserTest::class;

    protected string $title = 'Прохождение тестов';

    public function pages(): array
    {
        return [
            UserTestIndexPage::make($this->title()),
            UserTestFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            UserTestDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make('', [
                        ID::make()->sortable(),
                        Text::make('Result'),
                        Date::make('completed_at'),

                    ]),
                ]),
            ]),
        ];
    }
}
