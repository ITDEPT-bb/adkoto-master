<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::inRandomOrder()->first()->id,
            'buyer_id' => User::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
