<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@sikantin.com'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::firstOrCreate([
            'email' => 'penjual@sikantin.com'
        ], [
            'name' => 'Penjual Utama',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'store_name' => 'Kantin Sehat Ibu Ani',
            'status' => 'active'
        ]);

        // create vendor for the seller
        $seller = User::where('email', 'penjual@sikantin.com')->first();
        if ($seller) {
            Vendor::firstOrCreate([
                'slug' => 'kantin-sehat-ibu-ani'
            ], [
                'user_id' => $seller->id,
                'name' => $seller->store_name ?? 'Toko Penjual Utama',
                'description' => 'Toko contoh untuk Penjual Utama',
            ]);
        }

        User::firstOrCreate([
            'email' => 'user@sikantin.com'
        ], [
            'name' => 'Siswa Pembeli',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::firstOrCreate([
            'email' => 'supervisor@sikantin.com'
        ], [
            'name' => 'Kepala Sekolah',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        Category::firstOrCreate(['slug' => 'makanan-utama'], ['name' => 'Makanan Utama']);
        Category::firstOrCreate(['slug' => 'minuman'], ['name' => 'Minuman']);
        Category::firstOrCreate(['slug' => 'camilan'], ['name' => 'Camilan']);

        // Call new seeders
        $this->call([
            CategorySeeder::class,
            MenuItemSeeder::class,
            BankAccountSeeder::class,
        ]);
    }
}
