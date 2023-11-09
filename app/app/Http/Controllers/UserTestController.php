<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestQuestionAnswers;
use App\Models\UserTest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MoonShine\Http\Controllers\MoonShineController;

class UserTestController extends MoonShineController
{
    public function start(Test $test): View\View|\Illuminate\Foundation\Application|View\Factory|Application|RedirectResponse
    {
        return view('moonshine.tests.start', compact('test'));
    }

    /**
     * Валидирует ответы пользователя.
     * Подсчитывает результаты.
     * Сохраняет результаты в базе данных.
     * Перенаправляет пользователя на страницу с результатами.
     *
     * @param Request $request
     * @param $testId
     * @return RedirectResponse
     */
    public function answer(Request $request, $testId): RedirectResponse
    {
        // Валидация входных данных
        $data = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|exists:test_question_answers,id',
        ]);

        // Подсчет правильных ответов
        $score = 0;
        foreach ($data['questions'] as $questionId => $answerId) {
            $isRight = TestQuestionAnswers::where('id', $answerId)
                ->where('test_question_id', $questionId)
                ->where('is_right', 1)
                ->exists();
            if ($isRight) {
                $score++;
            }
        }

        // Подсчет общего количества вопросов
        $totalQuestions = count($data['questions']);

        // Расчет результата
        $result = ($score / $totalQuestions) * 100;

        $userId = Auth::id();
        if ($userId === null) {
            echo 'Пользователь не авторизован';
            die();
        }
        // Создание или обновление записи о прохождении теста
        $userTest = UserTest::updateOrCreate(
            [
                'user_id' => $userId,
                'test_id' => $testId,
            ],
            [
                'result' => $result,
                'completed_at' => now(),
            ]
        );

        // Перенаправление пользователя на страницу с результатами
        return redirect()->route('moonshine.tests.result', ['userTest' => $userTest->id]);
    }

    public function result(UserTest $userTest): View\View|\Illuminate\Foundation\Application|View\Factory|Application|RedirectResponse
    {
        return view('moonshine.tests.result', compact('userTest'));
    }
}
