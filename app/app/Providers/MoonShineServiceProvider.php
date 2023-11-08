<?php

namespace App\Providers;

use App\MoonShine\Resources\ArticleResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\CourseMaterialsResource;
use App\MoonShine\Resources\CourseResource;
use App\MoonShine\Resources\CourseTestsResource;
use App\MoonShine\Resources\CourseUsersResource;
use App\MoonShine\Resources\DictionaryResource;
use App\MoonShine\Resources\MaterialResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\OnboardingPlanResource;
use App\MoonShine\Resources\SettingResource;
use App\MoonShine\Resources\TestResource;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function menu(): array
    {
        return [
            MenuGroup::make('Администрирование', [

                MenuItem::make('Пользователи и Сотрудники', new MoonShineUserResource(), 'heroicons.outline.users'),
                MenuItem::make('Роли', new MoonShineUserRoleResource(), 'heroicons.outline.shield-exclamation'),
//                MenuItem::make('Сотрудники', new UserResource(), 'heroicons.outline.users'),
                MenuItem::make('Настройки', new SettingResource(), 'heroicons.outline.adjustments-vertical'),
                MenuItem::make('Связи курсов с сотрудниками', new CourseUsersResource()),
                MenuItem::make('Связи курсов с учебными материалами', new CourseMaterialsResource()),
                MenuItem::make('Связи курсов с тестами', new CourseTestsResource()),

            ], 'heroicons.outline.user-group')->canSee(static function () {
                return auth('moonshine')->user()->moonshine_user_role_id === 1;
            }),
            MenuGroup::make('Демо', [
                MenuItem::make('Статьи', new ArticleResource()),
                MenuItem::make('Категории', new CategoryResource()),
            ], 'heroicons.outline.gift'),
            MenuGroup::make('Онбординг и адаптация', [
                MenuItem::make('Планы онбординга и адаптации', new OnboardingPlanResource(), 'heroicons.map'),
                MenuItem::make('Учебные материалы', new MaterialResource(), 'heroicons.cursor-arrow-ripple'),
                MenuItem::make('Учебные курсы', new CourseResource(), 'heroicons.academic-cap'),
            ], 'heroicons.outline.newspaper'),

            MenuItem::make('Тесты', new TestResource()),
            MenuItem::make('Уведомления', new DictionaryResource()),
            MenuItem::make('Поддержка', new DictionaryResource())->badge(static fn() => $countMessages = '3'),
            MenuItem::make('Отчетность', new DictionaryResource()),


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
}
