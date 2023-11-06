<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MoonshineUserRole;
use MoonShine\Models\MoonshineUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
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
            'name' => 'HR-manager',
            'moonshine_user_role_id' => MoonshineUserRole::ADMIN_ROLE_ID,
            'email' => 'admin@admin.ru',
            'password' => bcrypt('admin@admin.ru1236541')
        ]);

        MoonshineUser::query()->create([
            'name' => 'HR-Менеджер',
            'moonshine_user_role_id' => MoonshineUserRole::HR_ROLE_ID,
            'email' => 'hr@admin.ru',
            'password' => bcrypt('hr@admin.ru123654')
        ]);

        MoonshineUser::query()->create([
            'name' => 'Менеджер',
            'moonshine_user_role_id' => MoonshineUserRole::MANAGER_ROLE_ID,
            'email' => 'manager@admin.ru',
            'password' => bcrypt('manager@admin.ru123654')
        ]);

        MoonshineUser::query()->create([
            'name' => 'Сотрудник',
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
    }
}
