<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Pieldefoca\Lux\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogCategoryFactory extends Factory
{
    protected $model = Post::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();

        return [
            'author_id' => 1,
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
