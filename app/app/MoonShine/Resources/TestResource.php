<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class TestResource extends ModelResource
{
    protected string $model = Test::class;

    protected string $title = 'Тесты и экзамены по онбордингу';

    public function fields(): array
    {
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make([
                    Block::make('Тест по онбордингу и адаптации', [
                        Text::make('Title')
                            ->required(),

                        Text::make('Description'),
                    ]),

                    HasMany::make('Вопросы этого теста', 'questions', resource: new TestQuestionsResource())
                        ->fields([
                            Id::make('id'),
                            Text::make('question'),
                        ]),

                ])->columnSpan(6),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
//            'description' => ['string', 'min:1'],
        ];
    }
}
