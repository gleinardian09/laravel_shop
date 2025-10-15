<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Bicycles',
            'Components',
            'Clothing & Apparel',
            'Tires & Tubes',
            'Tools & Maintenance',
            'Safety & Protection',
            'Bags & Storage',
            'Electronics & Accessories',
            'Nutrition & Hydration',
            'Parts & Upgrades',
            'Security',
            'Indoor Training',
            'Kids & Family',
            'Gifts & Novelties',
            'Helmets',
            'Accessories',
            'Peripherals',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => \Str::slug($name)],
                [
                    'name' => $name,
                    'description' => 'Category for ' . $name,
                    'is_active' => 1,
                ]
            );
        }
    }
}
