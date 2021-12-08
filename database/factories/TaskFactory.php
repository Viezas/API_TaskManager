<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nb_users = count(User::all());
        return [
            'body' => $this->faker->realText(200, 2),
            'user_id' => rand(1, $nb_users),
            'completed' => rand(0, 1)
        ];
    }
}
