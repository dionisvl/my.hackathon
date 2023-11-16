<?php

namespace App\Providers;

use App\MoonShine\Pages\Dashboard;
use App\MoonShine\Pages\Help\Help;
use App\MoonShine\Pages\Monitoring\Monitoring;
use App\MoonShine\Resources\CourseMaterialsResource;
use App\MoonShine\Resources\CourseResource;
use App\MoonShine\Resources\CourseTestsResource;
use App\MoonShine\Resources\CourseUsersResource;
use App\MoonShine\Resources\EmailLogsResource;
use App\MoonShine\Resources\MaterialResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\OnboardingPlanResource;
use App\MoonShine\Resources\SettingResource;
use App\MoonShine\Resources\TaskResource;
use App\MoonShine\Resources\TestQuestionAnswersResource;
use App\MoonShine\Resources\TestQuestionsResource;
use App\MoonShine\Resources\TestResource;
use App\MoonShine\Resources\UserTestResource;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MoonShine\Http\Middleware\ChangeLocale;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function menu(): array
    {
        return [
            MenuItem::make('Главная', Dashboard::make('Главная', 'dashboard')),
            MenuGroup::make('Администрирование', [

                MenuItem::make('Пользователи и Сотрудники', new MoonShineUserResource(), 'heroicons.outline.users'),
                MenuItem::make('Роли', new MoonShineUserRoleResource(), 'heroicons.outline.shield-exclamation'),
                MenuItem::make('Лог отправки email', new EmailLogsResource(), 'heroicons.outline.bars-4'),
                MenuItem::make('Настройки', new SettingResource(), 'heroicons.outline.adjustments-vertical'),
                MenuItem::make('Связи курсов с сотрудниками', new CourseUsersResource()),
                MenuItem::make('Связи курсов с учебными материалами', new CourseMaterialsResource()),
                MenuItem::make('Связи курсов с тестами', new CourseTestsResource()),

            ], 'heroicons.outline.user-group')->canSee(static function () {
                return isAdmin();
            }),

            MenuGroup::make('Онбординг и адаптация', [
                MenuItem::make('Планы онбординга и адаптации', new OnboardingPlanResource(), 'heroicons.map'),
                MenuItem::make('Учебные материалы', new MaterialResource(), 'heroicons.cursor-arrow-ripple'),
                MenuItem::make('Учебные курсы', new CourseResource(), 'heroicons.academic-cap'),
            ], 'heroicons.outline.newspaper')->canSee(static function () {
                return isAdmin();
            }),

            MenuGroup::make('Тесты', [
                MenuItem::make('Тесты', new TestResource())->badge(static fn() => 'AI'),
                MenuItem::make('Вопросы к тестам', new TestQuestionsResource()),
                MenuItem::make('Ответы к вопросам', new TestQuestionAnswersResource()),
                MenuItem::make('Результаты тестов', new UserTestResource()),
            ], 'heroicons.language')->canSee(static function () {
                return isAdmin();
            }),

            MenuItem::make('Задачи', new TaskResource())->badge(static fn() => 'AI'),
            MenuItem::make('Помощь', Help::make('Помощь', 'help')),

            // Кастомная страница
            MenuItem::make('Мониторинг обучения', Monitoring::make('Мониторинг обучения', 'monitoring'))->canSee(static function () {
                return isAdmin();
            })
        ];
    }

    protected function theme(): array
    {
        return [
            'colors' => [
                'primary' => '#5190fe',
                'secondary' => '#b62982',
            ],
            'darkColors' => [
                'primary' => '#5190fe',
                'secondary' => '#b62982',
            ]
        ];
    }

    protected array $middlewareGroups = [
        'moonshine' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
//            VerifyCsrfToken::class,
            SubstituteBindings::class,
            ChangeLocale::class,
        ],
    ];

    protected function registerRouteMiddleware(): void
    {
        $this->middlewareGroups['moonshine'] = array_merge(
            $this->middlewareGroups['moonshine'],
            config('moonshine.route.middlewares', [])
        );

        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
