<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'task'=>$this->faker->word,
            'description'=>$this->faker->word(),
            'status'=> $this->faker->randomElement(['income', 'outflow']),
            'value'=>$this->faker->numberBetween($min = 1, $max = 10000),
            'user_id'=> User::pluck('id')->random(),
        ];
    }
}
