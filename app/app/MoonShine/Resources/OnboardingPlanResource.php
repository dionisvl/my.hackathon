<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\OnboardingPlan;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Heading;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\ModelResource;

class OnboardingPlanResource extends ModelResource
{
    protected string $model = OnboardingPlan::class;

    protected string $title = 'Планы онбординга и адаптации';

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
                        TinyMce::make('Content')
                            ->addPlugins('code codesample media imagetools autolink link image')
                            ->addToolbar(' | code codesample | insertfile link image media')
                            ->required()
                            ->hideOnIndex(),
                    ]),

                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
            'content' => ['required', 'string', 'min:1'],
            'role_id' => ['int'],
        ];
    }
}
