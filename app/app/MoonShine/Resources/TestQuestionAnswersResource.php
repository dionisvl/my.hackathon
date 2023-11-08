<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TestQuestionAnswers;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\ModelResource;

class TestQuestionAnswersResource extends ModelResource
{
    protected string $model = TestQuestionAnswers::class;

    protected string $title = 'Ответы на вопросы по онбордингу';

    public function fields(): array
    {
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make([

                    Block::make('', [
                        Checkbox::make('Это правильный ответ', 'is_right')
                    ]),

                    BelongsTo::make('Ответ относится к вопросу:', 'question', resource: new TestQuestionsResource())
                        ->required(),

                    Block::make('Ответ', [

                        Tabs::make([
                            Tab::make('Answer', [
                                TinyMce::make('Answer')
                                    ->addPlugins('code codesample')
                                    ->addToolbar(' | code codesample')
                                    ->required()
                                    ->hideOnIndex(),
                            ]),
                        ]),
                    ]),

                ])->columnSpan(6),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'answer' => ['required', 'string', 'min:1'],
            'is_right' => ['bool'],
        ];
    }
}
