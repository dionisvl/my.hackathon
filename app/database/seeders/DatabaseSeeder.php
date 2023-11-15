<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterials;
use App\Models\CourseRole;
use App\Models\CourseTests;
use App\Models\CourseUsers;
use App\Models\EmailLogs;
use App\Models\Material;
use App\Models\MoonshineUser;
use App\Models\MoonshineUserRole;
use App\Models\OnboardingPlan;
use App\Models\Task;
use App\Models\Test;
use App\Models\TestQuestionAnswers;
use App\Models\TestQuestions;
use App\Models\User;
use App\Models\UserMaterial;
use App\Models\UserTest;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Сидирование базы данных
 */
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
        $this->courseRoles();
        $this->userMaterialsAndTests();
        $this->courseTestsAndMaterials();
        $this->tasks();
        $this->emailLogs();
    }

    private function onboardingPlans(): void
    {
        $roles = MoonshineUserRole::all();
        foreach ($roles as $role) {
            OnboardingPlan::query()->create([
                'title' => 'План адаптации для ' . $role->name,
                'content' => '
<ul style="list-style-type: decimal;">
    <li> - Welcome to the Proscom. Ваша задача: изучить все материалы и сдать все тесты из вашего курса.</li>
    <li> - Изучить наш корпоративный деловой этикет</li>
    <li> - Познакомиться с нашим Boris Daily</li>
    <li> - Выпить кофейку</li>
    <li> - PROFIT!</li>
</ul>
            ',
                'role_id' => $role->id,
            ]);
        }
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
     * Сидируем результаты изучения тестов и материалов у пользователей
     * @return void
     * @throws Exception
     */
    private function userMaterialsAndTests(): void
    {
        // Получаем случайные материалы и тесты
        $materials = Test::inRandomOrder()->take(2)->get();
        $tests = Test::inRandomOrder()->take(2)->get();
        $users = MoonshineUser::Query()->take(4)->get();
        foreach ($users as $user) {
            foreach ($tests as $test) {
                UserTest::query()->create([
                    'user_id' => $user->id,
                    'test_id' => $test->id,
                    'result' => ($user->id === MoonshineUserRole::WORKER_ROLE_ID) ? random_int(1, 50) : random_int(70, 99), // Случайный результат, например, в процентах
                    'completed_at' => Carbon::now()->subDays(random_int(1, 7)),
                ]);
            }
            foreach ($materials as $material) {
                UserMaterial::query()->create([
                    'user_id' => $user->id,
                    'material_id' => $material->id,
                    'viewed_at' => Carbon::now()->subDays(random_int(1, 7)),
                ]);
            }
        }
    }

    private function courseRoles(): void
    {
        CourseRole::query()->create([
            'course_id' => 2,
            'role_id' => MoonshineUserRole::ADMIN_ROLE_ID,
        ]);
        CourseRole::query()->create([
            'course_id' => 2,
            'role_id' => MoonshineUserRole::HR_ROLE_ID,
        ]);
        CourseRole::query()->create([
            'course_id' => 1,
            'role_id' => MoonshineUserRole::MANAGER_ROLE_ID,
        ]);
        CourseRole::query()->create([
            'course_id' => 1,
            'role_id' => MoonshineUserRole::WORKER_ROLE_ID,
        ]);

        // course_users:
        CourseUsers::query()->create([
            'course_id' => 2,
            'user_id' => MoonshineUserRole::ADMIN_ROLE_ID,
        ]);
    }

    /**
     * Сидируем привязку ко всем курсам всех материалов и всех тестов
     */
    private function courseTestsAndMaterials(): void
    {
        $courses = Course::all();
        $materials = Test::Query()->take(2)->get();
        $tests = Test::Query()->take(2)->get();
        foreach ($courses as $course) {
            foreach ($tests as $test) {
                CourseTests::query()->create([
                    'course_id' => $course->id,
                    'test_id' => $test->id,
                ]);
            }
            foreach ($materials as $material) {
                CourseMaterials::query()->create([
                    'course_id' => $course->id,
                    'material_id' => $material->id,
                ]);
            }
        }
    }

    /**
     * На каждого юзера заведём минимум по 2 задачки
     * @throws Exception
     */
    private function tasks(): void
    {
        $count = 0;
        $statuses = [Task::STATUS_NOT_STARTED, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED];
        $descriptions = ['Выпить кофейку', 'Познакомиться с нашим Boris Daily', 'Изучить наш корпоративный деловой этикет'];
        $userIds = MoonshineUser::pluck('id')->toArray();

        MoonshineUser::all()->each(function (MoonshineUser $user) use (&$count, $statuses, $descriptions, $userIds) {
            for ($i = 0; $i < 2; $i++) {
                $count++;
                $now = Carbon::now();
                $oneYearFromNow = Carbon::now()->addYear();
                $randomDateTime = Carbon::createFromTimestamp(random_int($now->timestamp, $oneYearFromNow->timestamp));

                Task::create([
                    'creator_id' => $userIds[array_rand($userIds)],
                    'assignee_id' => $user->id,
                    'status' => $statuses[array_rand($statuses)],
                    'title' => 'Автосгенерированная задача №' . $count,
                    'description' => $descriptions[array_rand($descriptions)],
                    'result' => '',
                    'deadline' => $randomDateTime,
                ]);
            }
        });
    }

    private function emailLogs(): void
    {
        $userIds = MoonshineUser::pluck('id')->toArray();
        $userEmails = MoonshineUser::pluck('email')->toArray();
        $typeList = ['Уведомление о дедлайне курса', 'Уведомление о незавершенной задаче'];
        for ($i = 0; $i < 2; $i++) {
            EmailLogs::create([
                'user_id' => $userIds[array_rand($userIds)],
                'email' => $userEmails[array_rand($userEmails)],
                'type' => $typeList[array_rand($typeList)],
            ]);
        }
    }
}
