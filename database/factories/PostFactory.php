<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Pieldefoca\Lux\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'author_id' => 1,
            'title' => $title,
            'slug' => str($title)->slug(),
            'excerpt' => fake()->text(),
            'body' => fake()->realText(),
        ];
    }
}
