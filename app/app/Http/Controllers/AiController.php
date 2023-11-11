<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;
use Throwable;

/*
 * Copyright (c) 2023 Denis Dudin. All rights reserved.
 * This code or any portion thereof may not be reproduced or used in any manner whatsoever
 * without the express written permission of the owner.
 */

class AiController extends Controller
{
    public function getReport(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $validated = $request->validate([
                'inputString' => 'required|string|max:1000',
            ]);

            $question = $validated['inputString'];
            $promptPrefix = 'Напиши отчет о проделанной работе. 3-4 предложения. Вот само задание по задаче: <';
            $promptPostfix = '>';
            $answer = $this->sendToNeuralNetwork($promptPrefix . $question . $promptPostfix);
        } catch (ValidationException $e) {
            return response($e->getMessage(), 400);
        } catch (Throwable $e) {
            return response($e->getMessage(), 400);
        }

        return response($answer, 200);
    }

    /**
     * Код для отправки запроса в API нейросети
     * @throws JsonException
     */
    protected function sendToNeuralNetwork($prompt): string
    {
        return getAi($prompt);
    }

    public function generateTest(Request $request)
    {
        try {
            $validated = $request->validate([
                'text' => 'required|string|max:1000',
            ]);

            // Формирование промпта для нейросети
            $prompt = $this->createPrompt($validated['text']);

            // Отправка запроса в API нейросети
            $response = $this->sendToNeuralNetwork($prompt);
            $newTestId = $this->saveTestFromJson($response);
            if (false === is_int($newTestId)) {
                throw new RuntimeException('Ошибка при парсинге JSON: ' . $response);
            }
        } catch (Throwable $e) {
            response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Тест успешно сохранен. testId=' . $newTestId]);
    }

    private function createPrompt(string $text): string
    {
        // Код для формирования промпта из полученного текста
        return "Создай тест в JSON формате на основе следующего текста: <" . $text . '>. Используй пример-шаблон: ' . self::getJsonExample() . ' . В твоём ответе должен быть только валидный JSON';
    }

    private static function getJsonExample(): string
    {
        return '{
    "title": "Название теста",
    "description": "Описание теста",
    "questions": [
        {
            "question": "Текст первого вопроса",
            "answers": [
                {
                    "answer": "Ответ 1",
                    "is_right": 0
                },
                {
                    "answer": "Ответ 2",
                    "is_right": 1
                }
                // Другие ответы...
            ]
        },
        // Другие вопросы...
    ]
}
';
    }


    /**
     * @throws JsonException
     */
    public function saveTestFromJson(string $jsonData): int
    {
        $jsonData = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        $test = Test::create([
            'title' => $jsonData['title'],
            'description' => $jsonData['description'],
        ]);

        foreach ($jsonData['questions'] as $qData) {
            $question = $test->questions()->create([
                'question' => $qData['question'],
            ]);

            foreach ($qData['answers'] as $aData) {
                $question->answers()->create([
                    'answer' => $aData['answer'],
                    'is_right' => $aData['is_right'],
                ]);
            }
        }
        return $test->id;
    }
}
