<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Metrics\ValueMetric;
use MoonShine\QueryTags\QueryTag;
use MoonShine\Resources\ModelResource;

class TaskResource extends ModelResource
{
    public string $sortColumn = 'created_at';
    protected string $model = Task::class;
    protected string $title = 'Задачи для прохождения онбординга';
    protected ?PageType $redirectAfterSave = PageType::INDEX;
    protected bool $editInModal = false;

    public function fields(): array
    {
        $html = <<<HTML
<span id="loadMessage" class="border border-black rounded inline-block bg-blue-500 px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition duration-200 ease-in-out cursor-pointer"
style="color: black !important; border: 1px black solid;">
  Сгенерировать отчет о работе с помощью нейросети
</span>
<div id="spinner" style="display: none; animation: blink 1s linear infinite;">Loading...</div>
<script>
document.getElementById('loadMessage').addEventListener('click', function() {
    const inputText = encodeURIComponent(tinymce.get('tiny-mce-1').getContent()); // Получение текста из TinyMCE редактора
    const url = '/admin/ai/getReport?inputString=' + inputText; // Построение URL с параметрами запроса

    document.getElementById('spinner').style.display = 'block';

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка сервера');
        }
        return response.text();
    })
    .then(message => {
        document.getElementById('spinner').style.display = 'none';
        tinymce.get('tiny-mce-2').setContent(message);
    })
    .catch(error => {
        document.getElementById('spinner').style.display = 'none';
        tinymce.get('tiny-mce-2').setContent(error.message);
    });
});
</script>
<style>
@keyframes blink {
  0% {opacity: 1;}
  50% {opacity: 0.5;}
  100% {opacity: 1;}
}
</style>
HTML;

        $task = new Task();

        if (isAdmin()) {
            // У администраторов полный доступ ко всем задачам.
            $ui = [
                Text::make('Title')
                    ->required(),

                BelongsTo::make('Автор', 'creator', static fn($item) => "$item->id. $item->name", resource: new MoonShineUserResource()),

                BelongsTo::make('Исполнитель', 'assignee', static fn($item) => "$item->id. $item->name", resource: new MoonShineUserResource()),

                Select::make('Статус', 'status')
                    ->options([
                        Task::STATUS_NOT_STARTED => $task->getLocalizedStatusAttribute(Task::STATUS_NOT_STARTED),
                        Task::STATUS_IN_PROGRESS => $task->getLocalizedStatusAttribute(Task::STATUS_IN_PROGRESS),
                        Task::STATUS_COMPLETED => $task->getLocalizedStatusAttribute(Task::STATUS_COMPLETED),
                    ])
                    ->sortable(),

                TinyMce::make('Описание', 'description')
                    ->addPlugins('code codesample')
                    ->addToolbar(' | code codesample')
                    ->required(),

                TinyMce::make('Результат выполнения', 'result')
                    ->addPlugins('code codesample')
                    ->addToolbar(' | code codesample')
                    ->hideOnIndex(),

                Preview::make('', 'updated_at', static fn() => $html)
                    ->hideOnIndex()
                    ->hideOnExport()
                    ->hideOnDetail(),

                Block::make('Дедлайн', [
                    Date::make('Дедлайн задачи', 'deadline')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->sortable()->default(Carbon::now()->addMonth()),

                    Date::make('Дата создания', 'created_at')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->sortable()
                        ->disabled(),

                    Date::make('Дата изменения', 'updated_at')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->sortable()
                        ->disabled(),


                ]),
            ];
        } else {
            // Если это обычный пользователь тогда у него будет ограниченный интерфейс Задач.
            $ui = [
                Text::make('Title')
                    ->disabled(),

                BelongsTo::make('Автор', 'creator', static fn($item) => "$item->id. $item->name", resource: new MoonShineUserResource())
                    ->disabled(),

                BelongsTo::make('Исполнитель', 'assignee', static fn($item) => "$item->id. $item->name", resource: new MoonShineUserResource())
                    ->disabled(),

                Select::make('Статус', 'status')
                    ->options([
                        Task::STATUS_NOT_STARTED => $task->getLocalizedStatusAttribute(Task::STATUS_NOT_STARTED),
                        Task::STATUS_IN_PROGRESS => $task->getLocalizedStatusAttribute(Task::STATUS_IN_PROGRESS),
                        Task::STATUS_COMPLETED => $task->getLocalizedStatusAttribute(Task::STATUS_COMPLETED),
                    ])
                    ->sortable()
                    ->required(),

                TinyMce::make('Описание задания', 'description')
                    ->addPlugins('code codesample')
                    ->addToolbar(' | code codesample')
                    ->disabled(),//Пользователь может только читать задание, но не изменять его

                TinyMce::make('Результат выполнения', 'result')
                    ->addPlugins('code codesample')
                    ->addToolbar(' | code codesample'),


                Block::make('Дедлайн', [
                    Date::make('Дедлайн задачи', 'deadline')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->disabled(),

                    Date::make('Дата создания', 'created_at')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->sortable()
                        ->default(Carbon::now())
                        ->disabled(),

                    Date::make('Дата изменения', 'updated_at')
                        ->format('Y-m-d H:i:s')
                        ->withTime()
                        ->useOnImport()
                        ->showOnExport()
                        ->sortable()
                        ->default(Carbon::now())
                        ->disabled(),
                ]),
            ];
        }
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make(

                    [] + $ui
                    + [
                        Preview::make('Preview', 'status', static fn() => 'some tessx<b> df </b>')
                    ],

                )->columnSpan(6),
            ]),


        ];
    }

    public function search(): array
    {
        return ['id', 'title', 'description'];
    }

    public function getActiveActions(): array
    {
        if (isAdmin() === false) {
            return ['view', 'update'];
        }
        return ['create', 'view', 'update', 'delete', 'massDelete'];
    }

    public function filters(): array
    {
        return [
            Text::make('Title'),

            BelongsTo::make('Исполнитель', 'assignee', static fn($item) => "$item->id. $item->name", resource: new MoonShineUserResource())
            ,
        ];
    }

    public function queryTags(): array
    {
        return [
            QueryTag::make(
                'Мои задачи',
                static fn(Builder $q) => $q->where('assignee_id', auth()->id())
            ),
            QueryTag::make(
                'Созданные не взятые в работу задачи',
                static fn(Builder $q) => $q->where('status', Task::STATUS_NOT_STARTED)
            ),
            QueryTag::make(
                'В работе',
                static fn(Builder $q) => $q->where('status', Task::STATUS_IN_PROGRESS)
            ),
            QueryTag::make(
                'Завершенные',
                static fn(Builder $q) => $q->where('status', Task::STATUS_COMPLETED)
            ),
        ];
    }

    public function metrics(): array
    {
        return [
            Grid::make([
                Column::make([
                    ValueMetric::make('Новые')
                        ->value(Task::query()->where('status', Task::STATUS_NOT_STARTED)->count()),
                ])->columnSpan(4),
                Column::make([
                    ValueMetric::make('В работе')
                        ->value(Task::query()->where('status', Task::STATUS_IN_PROGRESS)->count()),
                ])->columnSpan(4),
                Column::make([
                    ValueMetric::make('Завершенные')
                        ->value(Task::query()->where('status', Task::STATUS_COMPLETED)->count()),
                ])->columnSpan(4),
            ]),
        ];
    }

    public function query(): Builder
    {
        // Администраторы видят все задачи. Сотрудники: только свою.
        return parent::query()
            ->when(
                isAdmin() === false,
                fn($q) => $q->where('assignee_id', auth()->id())
            );
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
