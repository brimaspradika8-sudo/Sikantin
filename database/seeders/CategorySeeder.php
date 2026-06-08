<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Berat', 'icon' => '🍚'],
            ['name' => 'Makanan Ringan', 'icon' => '🥐'],
            ['name' => 'Minuman Panas', 'icon' => '☕'],
            ['name' => 'Minuman Dingin', 'icon' => '🧋'],
            ['name' => 'Dessert', 'icon' => '🍰'],
            ['name' => 'Snack', 'icon' => '🍪'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                ]
            );
        }
    }
}
