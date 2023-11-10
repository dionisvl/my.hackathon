<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\MoonshineUser;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Fields\Preview;
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
        /** @var MoonshineUser $thisUser */
        $thisUser = MoonshineUser::with('courses', 'plan')->where('id', auth()->id())->first();

        if (isAdmin()) {
            $helloText = 'Вы являетесь администратором системы. Поэтому вам доступна расширенная функциональность.
            У вас есть доступ на удаление всех сущностей.';
        } else {
            $helloText = 'Уважаемый сотрудник, добро пожаловать в нашу систему обординга, желаем успехов в изучении материала и сдачи тестов';
        }

        $course = $thisUser->courses->first();
        $courseString = $course->title ?? 'Курс адаптации не назначен, обратитесь к руководителю';

        // Роут пользователя где видно его прогресс по обучению
        $url = route('moonshine.user.progress', ['userId' => auth()->id()]);

        $plan = $thisUser->plan;
        $planTitle = $plan ? $plan->title : 'План онбординга не назначен';
        $planContent = $plan ? $plan->content : 'План онбординга не назначен';

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
                        ->value($thisUser->role->name),
                    TextBlock::make($planTitle, $planContent,),

                    ValueMetric::make('Автоматически назначенный вам курс обучения')
                        ->value($courseString),

                    Preview::make('Ознакомьтесь с вашим личным прогрессом онбординга по этой ссылке')
                        ->link($url, blank: false),

                ])->columnSpan(6),


            ]),
        ];
    }
}
