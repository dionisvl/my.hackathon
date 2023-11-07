<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Material;
use App\Models\MoonshineUser;
use App\Models\MoonshineUserRole;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return 'Сервис онбординга и адаптации сотрудников';
    }

    public function components(): array
	{
		return [
            TextBlock::make(
                'Добро пожаловать на онбординг!',
                'Демо версия'
            ),

            Grid::make([
                Column::make([
                    ValueMetric::make('Сотрудников на онбординге')
                        ->value(MoonshineUser::query()
                            ->where('moonshine_user_role_id', '=', MoonshineUserRole::WORKER_ROLE_ID)
                            ->count()),
                ])->columnSpan(6),

                Column::make([
                    ValueMetric::make('Обучающих материалов')
                        ->value(Material::query()->count()),
                ])->columnSpan(6),

                Column::make([
                    DonutChartMetric::make('Прогресс онбординга')
                        ->columnSpan(6)
                        ->values(['В процессе' => 100, 'Завершено' => 30]),
                ])->columnSpan(6),

                Column::make([
                    LineChartMetric::make('Кол-во изученного')
                        ->line([
                            'Материалы' => [
                                now()->format('Y-m-d') => 100,
                                now()->addDay()->format('Y-m-d') => 200
                            ]
                        ])
                        ->line([
                            'Тесты' => [
                                now()->format('Y-m-d') => 300,
                                now()->addDay()->format('Y-m-d') => 400
                            ]
                        ], '#EC4176'),
                ])->columnSpan(6)
            ]),
        ];
	}
}
