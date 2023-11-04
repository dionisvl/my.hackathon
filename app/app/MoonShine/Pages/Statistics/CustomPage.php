<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Statistics;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Pages\Page;

class CustomPage extends Page
{
    protected string $title = 'OrderStatisticsPage';
    protected string $subtitle = 'OrderStatisticsSubtitle';

    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'OrderStatistics';
    }

    public function components(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make([
                        TextBlock::make('Title 1', 'Text 1')
                    ])
                ])->columnSpan(6),
                Column::make([
                    Block::make([
                        TextBlock::make('Title 2', 'Text 2')
                    ])
                ])->columnSpan(6),
            ])
        ];
    }
}
