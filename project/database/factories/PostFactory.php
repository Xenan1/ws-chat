<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    public function definition(): array
    {
        return [
            'text' => $this->faker->text(600),
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ];
    }
}
