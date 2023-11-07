<?php

namespace App\MoonShine\Resources;

use App\Http\Controllers\MaterialController;
use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\LineBreak;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;

class MaterialResource extends ModelResource
{
    public string $model = Material::class;

    public string $title = 'Учебные материалы';

    public string $sortColumn = 'created_at';


    public string $column = 'title';
    protected ?PageType $redirectAfterSave = PageType::INDEX;

//
//    public bool $withPolicy = true;

//    public array $with = [
//        'user_materials',
//    ];

    public function rules(Model $item): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
            'content' => ['required', 'string', 'min:1'],
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
                        ActionButton::make(
                            'Ссылка на просмотр материала',
                            $this->getItem()?->getKey() ? route('moonshine.materials.show', $this->getItem()) : '/',
                        )
                            ->icon('heroicons.outline.paper-clip')
                            ->blank(),

                        LineBreak::make(),

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
                ])->columnSpan(6),

                Column::make([
                    Block::make('Контент', [

                        Tabs::make([
                            Tab::make('Content', [
                                TinyMce::make('Content')
                                    ->addPlugins('code codesample media imagetools autolink link image')
                                    ->addToolbar(' | code codesample | insertfile link image media')
                                    ->required()
                                    ->hideOnIndex(),
                            ]),
                        ]),


                    ]),
                ])->columnSpan(6),
            ]),
        ];
    }

    public function queryTags(): array
    {
        return [
//            QueryTag::make(
//                'Изученные материалы',
//                static fn(Builder $q) => $q->whereNotNull('user_id')
//            ),

//            QueryTag::make(
//                'Неизученные материалы',
//                static fn(Builder $q) => $q
//                    ->leftJoin('user_materials', 'user_materials.material_id', '=', 'materials.id')
//                    ->whereNull('user_materials.user_id')
//                    ->orderBy('user_materials.created_at', 'desc')
//            )->icon('heroicons.outline.users'),
        ];
    }

    public function metrics(): array
    {
        return [
            Grid::make([
                Column::make([
                    ValueMetric::make('Materials')
                        ->value(Material::query()->count()),
                ])->columnSpan(6),
            ]),
        ];
    }


    public function search(): array
    {
        return ['id', 'title', 'content'];
    }

    public function filters(): array
    {
        return [
            Text::make('Title'),

            Text::make('Content'),
        ];
    }

    public function buttons(): array
    {
        return [
            ActionButton::make(
                'Go to',
                static fn(Material $model) => route('moonshine.materials.show', $model)
            )->icon('heroicons.outline.document-arrow-down'),
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('mass-active', [MaterialController::class, 'massActive'])
            ->name('materials.mass-active');
    }
}
