<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\UserTest;
use App\MoonShine\Pages\UserTest\UserTestDetailPage;
use App\MoonShine\Pages\UserTest\UserTestFormPage;
use App\MoonShine\Pages\UserTest\UserTestIndexPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class UserTestResource extends ModelResource
{
    protected string $model = UserTest::class;

    protected string $title = 'Сведения об прохождении тестов сотрудниками';

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
                        BelongsTo::make('Тест', 'test', static fn($item) => (string)$item->title, new TestResource()),
                        Date::make('Дата прохождения теста', 'completed_at'),
                        Text::make('Оценка', 'result'),
                        BelongsTo::make('Пользователь', 'user', resource: new MoonShineUserResource())
                    ]),
                ]),
            ]),
        ];
    }

    public function buttons(): array
    {
        return [
            ActionButton::make(
                'Просмотреть результат',
                static fn(UserTest $model) => route('moonshine.tests.result', $model)
            )->icon('heroicons.outline.paper-clip'),
        ];
    }
}
