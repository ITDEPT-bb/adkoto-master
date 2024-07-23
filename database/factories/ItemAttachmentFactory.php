<?php

namespace Database\Factories;

use App\Models\ItemAttachment;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemAttachmentFactory extends Factory
{
    protected $model = ItemAttachment::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::inRandomOrder()->first()->id,
            'path' => $this->faker->imageUrl,
        ];
    }
}
