<?php

namespace Database\Factories;

use App\Models\MarketMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketMessageFactory extends Factory
{
    protected $model = MarketMessage::class;

    public function definition(): array
    {
        return [
            'sender_id' => User::inRandomOrder()->first()->id,
            'receiver_id' => User::inRandomOrder()->where('id', '!=', $this->faker->unique()->numberBetween(1, User::count()))->first()->id,
            'content' => $this->faker->sentence,
        ];
    }
}
