<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition()
    {
        return [
            'chat_id' => $this->faker->text(16),
            'user_id' => User::factory(),
            'username' => $this->faker->userName,
        ];
    }
}
