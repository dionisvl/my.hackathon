<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Monitoring;

use App\Models\Material;
use App\Models\MoonshineUser;
use App\Models\UserMaterial;
use App\Models\UserTest;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Pages\Page;

class Monitoring extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Monitoring';
    }

    public function components(): array
    {
        [$countFinished, $countProcess] = MoonshineUser::getCountStat();
        $materialStat = UserMaterial::getStat();
        $testStat = UserTest::getStat();
        return [
            Grid::make([
                Column::make([
                    ValueMetric::make('Сотрудников на онбординге')
                        ->value(MoonshineUser::all()->count()),

                    ValueMetric::make('Обучающих материалов')
                        ->value(Material::all()->count()),
                ])->columnSpan(6),

                Column::make([
                    DonutChartMetric::make('Прогресс онбординга')
                        ->columnSpan(6)
                        ->values(['В процессе' => $countProcess, 'Завершено' => $countFinished]),
                ])->columnSpan(6),

                Column::make([
                    LineChartMetric::make('Кол-во изученного')
                        ->line([
                            'Изученные материалы' => [
                                    now()->subDays(20)->format('Y-m-d') => 2,
                                    now()->subDays(10)->format('Y-m-d') => 4,
                                ] + $materialStat
                        ])
                        ->line([
                            'Пройденные тесты' => [
                                    now()->subDays(20)->format('Y-m-d') => 3,
                                    now()->subDays(10)->format('Y-m-d') => 6,
                                ] + $testStat
                        ], '#EC4176'),
                ])->columnSpan(6),
            ])


        ];
    }
}
