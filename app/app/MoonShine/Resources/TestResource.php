<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class TestResource extends ModelResource
{
    protected string $model = Test::class;

    protected string $title = 'Тесты и экзамены по онбордингу';

    public function fields(): array
    {
        return [
            ID::make()
                ->useOnImport()
                ->showOnExport()
                ->sortable(),

            Grid::make([
                Column::make([
                    Block::make('Тест по онбордингу и адаптации', [
                        Text::make('Title')
                            ->required(),

                        Text::make('Description'),
                    ]),

                    HasMany::make('Вопросы этого теста', 'questions', resource: new TestQuestionsResource())
                        ->fields([
                            Id::make('id'),
                            Text::make('question'),
                        ]),

                ])->columnSpan(6),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
//            'description' => ['string', 'min:1'],
        ];
    }

    public function buttons(): array
    {
        $html = <<<HTML
<div>Вставьте сюда ваш текст для теста: </div>
<textarea id="aiTextarea" style="min-width: 600px;"></textarea>
<span id="loadMessage" class="inline-block bg-blue-500 px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition duration-200 ease-in-out cursor-pointer" style="color: black !important;">
  Сгенерировать тест на основе сырого текста с помощью нейросети
</span>
<div id="responseArea"></div>
<div id="spinner" style="display: none; animation: blink 1s linear infinite;">Loading...</div>
<script>
document.getElementById('loadMessage').addEventListener('click', function() {
    const inputText = document.getElementById('aiTextarea').value;
    const url = '/admin/ai/generateTest';

    document.getElementById('spinner').style.display = 'block';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ text: inputText })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка сервера');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('spinner').style.display = 'none';
        if (data.status === 'success') {
            document.getElementById('responseArea').innerText = data.message;
        }else if(data.message) {
            document.getElementById('responseArea').innerText = data.message;
        }else {
            document.getElementById('responseArea').innerText = 'Произошла ошибка сервера';
        }
    })
    .catch(error => {
        document.getElementById('spinner').style.display = 'none';
        document.getElementById('responseArea').innerText = error.message;
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

        return [
            ActionButton::make(
                'Пройти этот тест',
                static fn(Test $model) => route('moonshine.tests.start', $model)
            )->icon('heroicons.outline.paper-clip'),

            ActionButton::make(
                label: 'Генерация теста AI',
            //url: '/admin/ai/getModal',
            )->inModal(
                title: fn() => 'Генерация теста AI',
                content: fn() => $html,
                async: false
            ),
        ];
    }
}
