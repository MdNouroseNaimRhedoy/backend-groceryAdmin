<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // some products with placeholder image

        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description for product 1',
                'price' => 100,
                'stock' => 10,
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for product 2',
                'price' => 200,
                'stock' => 20,
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description for product 3',
                'price' => 300,
                'stock' => 30,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
