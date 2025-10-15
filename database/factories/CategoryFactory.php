<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = \App\Models\Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'description' => $this->faker->sentence,
            'is_active' => true,
        ];
    }

    // ✅ Custom state method used in your test
    public function named($name)
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'slug' => \Str::slug($name),
        ]);
    }
}
