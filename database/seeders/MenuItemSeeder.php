<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('role', 'seller')->first();
        if (!$seller) {
            return;
        }

        $menuItems = [
            // Makanan Berat
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur dan sayuran',
                'price' => 18000,
                'category' => 'Makanan Berat',
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng dengan topping telur dan sosis',
                'price' => 16000,
                'category' => 'Makanan Berat',
            ],
            [
                'name' => 'Ayam Goreng',
                'description' => 'Ayam goreng renyah dengan nasi putih',
                'price' => 22000,
                'category' => 'Makanan Berat',
            ],
            // Makanan Ringan
            [
                'name' => 'Roti Bakar',
                'description' => 'Roti bakar dengan selai dan margarin',
                'price' => 8000,
                'category' => 'Makanan Ringan',
            ],
            [
                'name' => 'Croissant',
                'description' => 'Croissant premium dengan cokelat',
                'price' => 12000,
                'category' => 'Makanan Ringan',
            ],
            // Minuman Panas
            [
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam premium tanpa gula',
                'price' => 8000,
                'category' => 'Minuman Panas',
            ],
            [
                'name' => 'Teh Manis',
                'description' => 'Teh manis hangat yang menyegarkan',
                'price' => 5000,
                'category' => 'Minuman Panas',
            ],
            // Minuman Dingin
            [
                'name' => 'Es Teh',
                'description' => 'Es teh dingin dengan lemon',
                'price' => 6000,
                'category' => 'Minuman Dingin',
            ],
            [
                'name' => 'Jus Buah',
                'description' => 'Jus buah segar campuran',
                'price' => 10000,
                'category' => 'Minuman Dingin',
            ],
            // Dessert
            [
                'name' => 'Donat',
                'description' => 'Donat empuk dengan berbagai topping',
                'price' => 7000,
                'category' => 'Dessert',
            ],
            [
                'name' => 'Pudding',
                'description' => 'Pudding cokelat lezat',
                'price' => 9000,
                'category' => 'Dessert',
            ],
            // Snack
            [
                'name' => 'Keripik',
                'description' => 'Keripik kentang gurih',
                'price' => 5000,
                'category' => 'Snack',
            ],
        ];

        foreach ($menuItems as $item) {
            $category = Category::where('name', $item['category'])->first();

            MenuItem::updateOrCreate(
                ['name' => $item['name'], 'seller_id' => $seller->id],
                [
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'category_id' => $category?->id,
                    'stock' => 999,
                    'is_available' => true,
                ]
            );
        }
    }
}
