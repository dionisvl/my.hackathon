<?php

namespace App\Providers;

use App\Models\Comment;
use App\MoonShine\Resources\ArticleResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\CommentResource;
use App\MoonShine\Resources\DictionaryResource;
use App\MoonShine\Resources\SettingResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use App\MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function menu(): array
    {
        return [
            MenuGroup::make('Администрирование', [
                MenuItem::make('Администраторы', new MoonShineUserResource(), 'heroicons.outline.users'),
                MenuItem::make('Права доступа', new MoonShineUserRoleResource(), 'heroicons.outline.shield-exclamation'),
                MenuItem::make('Сотрудники', new UserResource(), 'heroicons.outline.users'),
                MenuItem::make('Настройки', new SettingResource(), 'heroicons.outline.adjustments-vertical'),
            ], 'heroicons.outline.user-group')->canSee(static function () {
                return auth('moonshine')->user()->moonshine_user_role_id === 1;
            }),

            MenuGroup::make('Материалы', [
                MenuItem::make('Статьи', new ArticleResource()),
                MenuItem::make('Категории', new CategoryResource()),
            ], 'heroicons.outline.newspaper'),

            MenuItem::make('Тесты', new DictionaryResource()),
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
