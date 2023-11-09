<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TestQuestions;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\ModelResource;

class TestQuestionsResource extends ModelResource
{
    protected string $model = TestQuestions::class;

    protected string $title = 'Вопросы по онбордингу';

    public function fields(): array
    {
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make([
                    BelongsTo::make('Вопрос относится к тесту:', 'test', static fn($item) => "$item->id. $item->title", resource: new TestResource())
                        ->required(),

                    Block::make('Вопрос', [

                        Tabs::make([
                            Tab::make('Question', [
                                TinyMce::make('Question')
                                    ->addPlugins('code codesample')
                                    ->addToolbar(' | code codesample')
                                    ->required()
                                    ->hideOnIndex(),
                            ]),
                        ]),
                    ]),


                    HasMany::make('Ответы к этому вопросу', 'answers', resource: new TestQuestionAnswersResource())
                        ->fields([
                            Id::make('id'),
                            Text::make('answer'),
                            Checkbox::make('is_right'),
                        ]),

                ])->columnSpan(6),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'question' => ['required', 'string', 'min:1'],
        ];
    }
}
