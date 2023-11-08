<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Course;
use App\Models\Material;
use App\Models\MoonshineUserRole;
use App\Models\OnboardingPlan;
use App\Models\Test;
use App\Models\TestQuestionAnswers;
use App\Models\TestQuestions;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MoonShine\Models\MoonshineUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        MoonshineUserRole::query()->create([
            'id' => MoonshineUserRole::HR_ROLE_ID,
            'name' => 'HR-manager'
        ]);
        MoonshineUserRole::query()->create([
            'id' => MoonshineUserRole::MANAGER_ROLE_ID,
            'name' => 'Manager'
        ]);
        MoonshineUserRole::query()->create([
            'id' => MoonshineUserRole::WORKER_ROLE_ID,
            'name' => 'Worker'
        ]);

        MoonshineUser::query()->create([
            'name' => 'Иван Администратор и HR-manager на пол ставки',
            'moonshine_user_role_id' => MoonshineUserRole::ADMIN_ROLE_ID,
            'email' => 'admin@admin.ru',
            'password' => bcrypt('admin@admin.ru1236541')
        ]);

        MoonshineUser::query()->create([
            'name' => 'HR-Менеджер Анна Васильевна',
            'moonshine_user_role_id' => MoonshineUserRole::HR_ROLE_ID,
            'email' => 'hr@admin.ru',
            'password' => bcrypt('hr@admin.ru123654')
        ]);

        MoonshineUser::query()->create([
            'name' => 'Менеджер Иннокентий',
            'moonshine_user_role_id' => MoonshineUserRole::MANAGER_ROLE_ID,
            'email' => 'manager@admin.ru',
            'password' => bcrypt('manager@admin.ru123654')
        ]);

        MoonshineUser::query()->create([
            'name' => 'Сотрудник просто Петрович',
            'moonshine_user_role_id' => MoonshineUserRole::WORKER_ROLE_ID,
            'email' => 'worker@admin.ru',
            'password' => bcrypt('worker@admin.ru123654')
        ]);


        Article::factory(20)->create();
        Category::factory(10)->create();


        User::factory(10)->create();

        DB::table('settings')->insert([
            'id' => 1,
            'email' => fake()->email(),
            'phone' => fake()->e164PhoneNumber(),
            'copyright' => now()->year
        ]);

        $this->onboardingPlans();
        $this->materials();
        $this->courses();
        $this->tests();
        $this->testUser();
    }

    private function onboardingPlans(): void
    {
        OnboardingPlan::query()->create([
            'title' => 'План адаптации проектного менеджера',
            'content' => '
            - Welcome to the Proscom
            - Деловой этикет онлайн и офлайн-встреч
            - Познакомиться с нашим Boris Daily в Google Chat
            ',
            'role_id' => MoonshineUserRole::WORKER_ROLE_ID,
        ]);
        OnboardingPlan::query()->create([
            'title' => 'Онбординг QA специалиста',
            'content' => '
            - Welcome to the Proscom
            - Деловой этикет QA и QA-auto
            - Познакомиться с нашим QA Daily
            ',
            'role_id' => MoonshineUserRole::WORKER_ROLE_ID,
        ]);
    }

    private function materials(): void
    {
        Material::query()->create([
            'title' => 'Зона ответственности проектного менеджера',
            'content' => 'Знания и компетенции хорошего менеджера проектов должны быть достаточно широки для того, чтобы грамотно управлять проектами на специфическом рынке. Но прежде чем залезать в соседние области знаний, нужно бесперебойно покрывать основную зону ответственности ПМа.
Ниже чек лист обязанностей, выполнение которых должно быть доведено до автоматизма.
Некоторые задачи должны выполняться совместно с командой. Только так они будут сделаны быстро и качественно, поэтому не геройствуйте и не пытайтесь тянуть их в одиночку.
',
//            'files' => '',
        ]);
        Material::query()->create([
            'title' => 'Принципы ведения проектов и обязательные атрибуты',
            'content' => 'Материал класс 2022',
        ]);
    }

    private function courses(): void
    {
        Course::query()->create([
            'title' => 'Project Management - PROSCOM новый курс, обновлённый 2022',
            'description' => 'Это наш новый курс прям доработанный , все учтено',
            'start_at' => '2022-11-07 18:35:03',
            'deadline_at' => '2023-11-09 18:35:03',
            'end_at' => '2023-11-20 18:35:03',
        ]);
        Course::query()->create([
            'title' => 'Design - Proscom обычный курс, 2023',
            'description' => 'новый курс доработанный',
            'start_at' => '2022-10-07 18:35:03',
            'end_at' => '2023-12-20 18:35:03',
        ]);
    }

    private function tests(): void
    {
        // Тест 1
        $test1 = Test::query()->create([
            'title' => 'Основы программирования',
            'description' => 'Введение в основы программирования.',
        ]);

        // Вопросы и ответы для Теста 1
        $questionsAnswers1 = [
            [
                'question' => 'Что такое переменная?',
                'answers' => [
                    ['answer' => 'Контейнер для хранения данных', 'is_right' => 1],
                    ['answer' => 'Имя программы', 'is_right' => 0],
                    ['answer' => 'Тип данных', 'is_right' => 0],
                ],
            ],
            [
                'question' => 'Что такое цикл?',
                'answers' => [
                    ['answer' => 'Повторяющаяся последовательность действий', 'is_right' => 1],
                    ['answer' => 'Тип данных', 'is_right' => 0],
                    ['answer' => 'Метод ввода данных', 'is_right' => 0],
                ],
            ],
            [
                'question' => 'Что такое функция?',
                'answers' => [
                    ['answer' => 'Блок кода, который можно вызвать по имени', 'is_right' => 1],
                    ['answer' => 'Тип переменной', 'is_right' => 0],
                    ['answer' => 'Способ организации кода', 'is_right' => 0],
                ],
            ],
        ];

        // Создание вопросов и ответов для Теста 1
        foreach ($questionsAnswers1 as $qa) {
            $question = TestQuestions::query()->create([
                'test_id' => $test1->id,
                'question' => $qa['question'],
            ]);

            foreach ($qa['answers'] as $answer) {
                TestQuestionAnswers::query()->create([
                    'test_question_id' => $question->id,
                    'answer' => $answer['answer'],
                    'is_right' => $answer['is_right'],
                ]);
            }
        }

        $test2 = Test::query()->create([
            'title' => 'Основы продуктовой аналитики',
            'description' => 'Введение в основы продуктовой аналитики.',
        ]);

        // Вопросы и ответы для Теста 2
        $questionsAnswers2 = [
            [
                'question' => 'Что такое A/B тестирование?',
                'answers' => [
                    ['answer' => 'Метод сравнения двух вариантов продукта', 'is_right' => 1],
                    ['answer' => 'Тестирование безопасности продукта', 'is_right' => 0],
                    ['answer' => 'Тестирование производительности продукта', 'is_right' => 0],
                ],
            ],
            [
                'question' => 'Что такое воронка продаж?',
                'answers' => [
                    ['answer' => 'Модель, описывающая путь клиента от первого контакта до покупки', 'is_right' => 1],
                    ['answer' => 'Список всех продаж продукта', 'is_right' => 0],
                    ['answer' => 'Статистика продаж продукта', 'is_right' => 0],
                ],
            ],
            [
                'question' => 'Что такое KPI?',
                'answers' => [
                    ['answer' => 'Ключевые показатели эффективности', 'is_right' => 1],
                    ['answer' => 'Код продукта', 'is_right' => 0],
                    ['answer' => 'Категория продукта', 'is_right' => 0],
                ],
            ],
        ];

        // Создание вопросов и ответов для Теста 2
        foreach ($questionsAnswers2 as $qa) {
            $question = TestQuestions::query()->create([
                'test_id' => $test2->id,
                'question' => $qa['question'],
            ]);

            foreach ($qa['answers'] as $answer) {
                TestQuestionAnswers::query()->create([
                    'test_question_id' => $question->id,
                    'answer' => $answer['answer'],
                    'is_right' => $answer['is_right'],
                ]);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function testUser(): void
    {
        // Получаем случайные тесты
        $tests = Test::inRandomOrder()->take(2)->get();
        foreach ($tests as $test) {
            DB::table('user_tests')->insert([
                'user_id' => 1,
                'test_id' => $test->id,
                'result' => random_int(0, 100), // Случайный результат, например, в процентах
                'completed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
