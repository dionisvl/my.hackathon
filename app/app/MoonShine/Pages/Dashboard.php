<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\MoonshineUser;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
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
        $helloText = '';
        if (isAdmin()) {
            $helloText = 'Вы являетесь администратором системы. Поэтому вам доступна расширенная функциональность.
            У вас есть доступ на удаление всех сущностей.';
        } else {
            $helloText = 'Уважаемый сотрудник, добро пожаловать в нашу систему обординга, желаем успехов в изучении материала и сдачи тестов';
        }
        $course = MoonshineUser::with('courses')->where('id', auth()->id())->first()->courses->first();
        $courseString = $course->title ?? 'Курс адаптации не назначен, обратитесь к руководителю';

        return [
            Block::make('', [
                TextBlock::make(
                    'Добро пожаловать на онбординг! ',
                    'Демо версия. ' . $helloText
                ),
            ]),
            Divider::make(),
            Grid::make([
                Column::make([
                    ValueMetric::make('Ваша должность')
                        ->value(MoonshineUser::query()->where('id', auth()->id())->first()->role->name),
                    ValueMetric::make('Автоматически назначенный вам курс обучения')
                        ->value($courseString),

                ])->columnSpan(6),

            ]),
        ];
    }
}
