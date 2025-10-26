<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // <-- Import the Product model

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear the table first to avoid duplicates on re-seeding
        Product::truncate();

        Product::create([
            'name' => 'Fancy Gadget',
            'description' => 'An amazing gadget that does everything you want.',
            'price' => 99.99,
            'image_url' => 'https://via.placeholder.com/150'
        ]);

        Product::create([
            'name' => 'Super Widget',
            'description' => 'A high-quality widget for all your widget needs.',
            'price' => 45.50,
            'image_url' => 'https://via.placeholder.com/150'
        ]);

        Product::create([
            'name' => 'Simple Thingamajig',
            'description' => 'Sometimes the simplest things are the best.',
            'price' => 19.95,
            'image_url' => 'https://via.placeholder.com/150'
        ]);
    }
}