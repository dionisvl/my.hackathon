<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\MoonshineUser;
use App\Models\Test;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class UserTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'user_id' => MoonshineUser::inRandomOrder()->first(),
            'test_id' => Test::inRandomOrder()->first(),
            'result' => random_int(1, 100),
            'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
