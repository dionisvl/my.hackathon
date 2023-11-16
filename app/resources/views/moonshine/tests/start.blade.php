@extends('layouts.app')

@section('content')

    <div>
        <div class="prose
            min-w-full
            prose-img:rounded-xl
            prose-invert"
        >
            <x-title>Тест: {{ $test->title }}</x-title>
            <div>{{ $test->description }}</div>

            @if (isset($test->userTest->completed_at))
                <blockquote>
                    <div class="mt-4">
                        <div>Тест уже был пройден ранее.</div>
                        <div>Дата прохождения теста: {{ $userTest->completed_at }}</div>
                        <div>Результат прохождения теста: {{ $userTest->result }}</div>
                    </div>
                </blockquote>
            @endif

            <form class="text-black" action="{{ route('moonshine.tests.answer', ['testId' => $test->id]) }}"
                  method="POST">
                @csrf <!-- CSRF token для безопасности -->
                @foreach ($test->questions as $question)
                    <fieldset>
                        <legend>Вопрос: {{ $question->question }}</legend>
                        @foreach ($question->answers as $index => $answer)
                            <div>
                                <input type="radio" name="questions[{{ $question->id }}]"
                                       id="question_{{ $question->id }}_answer_{{ $index }}" value="{{ $answer->id }}">
                                <label
                                    for="question_{{ $question->id }}_answer_{{ $index }}">{{ $answer->answer }}</label>
                            </div>
                        @endforeach
                    </fieldset>
                @endforeach
                <button type="submit" class="btn btn-primary">Отправить ответы</button>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const form = document.querySelector('form');
                    form.addEventListener('submit', function (event) {
                        const questions = document.querySelectorAll('fieldset');
                        for (const question of questions) {
                            const inputs = question.querySelectorAll('input[type="radio"]');
                            const hasChecked = Array.from(inputs).some(input => input.checked);
                            if (!hasChecked) {
                                event.preventDefault(); // предотвратить отправку формы
                                alert('Пожалуйста, выберите ответ на каждый вопрос.');
                                return; // выходим из функции, не проверяя остальные вопросы
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>

@endsection
