<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Help;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Fields\Preview;
use MoonShine\Pages\Page;

class Help extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Помощь';
    }

    public function components(): array
    {
        return [
            Grid::make([

                Column::make([

                    Block::make('', [
                        TextBlock::make(
                            'Уважаемый сотрудник, вам назначен персональный ментор для прохождения онбординга ',
                            'Перейдите по ссылке для коммуникации с ним: '
                        ),
                        Preview::make('Ссылка:')
                            ->link('https://bit.ly/45RlR1I', blank: false),
                    ]),


                ])->columnSpan(6),
            ])


        ];
    }
}
