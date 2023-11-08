<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Heading;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class CourseResource extends ModelResource
{
    public string $sortColumn = 'created_at';
    protected string $model = Course::class;
    protected string $title = 'Учебные курсы';

    public function rules(Model $item): array
    {
        return [
            'title' => ['string', 'min:1'],
            'description' => ['string', 'min:1'],
            'start_at' => ['required', 'date'],
            'deadline_at' => ['nullable', 'date'],
            'end_at' => ['required', 'date'],
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make([
                    Block::make('Заголовок', [
                        Collapse::make('Title', [
                            Heading::make('Title'),

                            Flex::make('flex-titles', [
                                Text::make('Title')
                                    ->withoutWrapper()
                                    ->required(),
                            ])
                                ->justifyAlign('start')
                                ->itemsAlign('start'),
                        ]),

                    ]),

                    Block::make('Описание', [
                        Text::make('description'),
                    ]),

                    Block::make('Дата начала курса', [
                        Date::make('Дата начала курса', 'start_at')
                            ->format('Y-m-d H:i:s')
                            ->withTime()
                    ]),

                    Block::make('Дедлайн курса (время рассылки уведомления)', [
                        Date::make('Дедлайн курса', 'deadline_at')
                            ->format('Y-m-d H:i:s')
                            ->withTime()
                    ]),

                    Block::make('Дата окончания курса', [
                        Date::make('Дата окончания курса', 'end_at')
                            ->format('Y-m-d H:i:s')
                            ->withTime()
                    ]),
                ])->columnSpan(6),


                Column::make([

                    Block::make('', [
                        BelongsToMany::make('Сотрудники на курсе', 'users', resource: new MoonshineUserResource()),
                        BelongsToMany::make('Учебные материалы курса', 'materials', resource: new MaterialResource())
//                        ->fields([
//                            Text::make('Title'),
//                            Text::make('Content'),
//                        ])
                        ,
                        BelongsToMany::make('Тесты этого курса', 'tests', resource: new CourseTestsResource()),
//                            ->valuesQuery(fn($query) => $query->where('id', 1))
                    ]),

                ])->columnSpan(6),

            ]),
        ];
    }
}
