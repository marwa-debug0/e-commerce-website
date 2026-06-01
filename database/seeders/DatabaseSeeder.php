<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed regular customer account
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // 2. Seed dedicated administrator account
        $admin = User::factory()->create([
            'name' => 'Admin Manager',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // 3. Create categories
        $lighting = \App\Models\Category::create([
            'name' => 'Lighting',
            'slug' => 'lighting',
        ]);

        $accessories = \App\Models\Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);

        $furniture = \App\Models\Category::create([
            'name' => 'Furniture',
            'slug' => 'furniture',
        ]);

        // 4. Create coupons
        \App\Models\Coupon::create([
            'code' => 'BAUHAUS10',
            'type' => 'percent',
            'value' => 10.00,
            'status' => 'active',
            'min_spend' => 0.00,
        ]);

        \App\Models\Coupon::create([
            'code' => 'MINIMAL50',
            'type' => 'fixed',
            'value' => 50.00,
            'status' => 'active',
            'min_spend' => 200.00,
        ]);

        // 5. Seed sample products with SKUs and stock levels
        $poster = Product::create([
            'title' => 'Bauhaus Poster Series',
            'description' => 'A set of geometric lithographs celebrating early modern art layout grids.',
            'price' => 45.00,
            'image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=500',
            'category_id' => $accessories->id,
            'stock' => 15,
            'sku' => 'ACC-POST-001',
        ]);

        $lamp = Product::create([
            'title' => 'Minimalist Table Lamp',
            'description' => 'Sphere and stick metal desk lamp designed with raw geometric steel materials.',
            'price' => 120.00,
            'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500',
            'category_id' => $lighting->id,
            'stock' => 3, // Low stock alert
            'sku' => 'LGT-LAMP-002',
        ]);

        $clock = Product::create([
            'title' => 'Geometric Wall Clock',
            'description' => 'Black steel frame clock with clean sans-serif numerals. No noise, pure form.',
            'price' => 85.00,
            'image_url' => 'https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?w=500',
            'category_id' => $accessories->id,
            'stock' => 0, // Out of stock
            'sku' => 'ACC-CLCK-003',
        ]);

        $bookends = Product::create([
            'title' => 'Concrete Bookend Set',
            'description' => 'Pair of raw cast concrete bookends. Heavy, minimal, architectural.',
            'price' => 60.00,
            'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=500',
            'category_id' => $accessories->id,
            'stock' => 8,
            'sku' => 'ACC-BEND-004',
        ]);

        $pillow = Product::create([
            'title' => 'Linen Throw Pillow',
            'description' => 'Natural undyed linen cushion. No pattern, no color — just texture and form.',
            'price' => 35.00,
            'image_url' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=500',
            'category_id' => $accessories->id,
            'stock' => 12,
            'sku' => 'ACC-PILW-005',
        ]);

        $notebook = Product::create([
            'title' => 'Steel Pen + Notebook Set',
            'description' => 'Brushed steel ballpoint pen paired with a grid-ruled hardcover notebook.',
            'price' => 55.00,
            'image_url' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=500',
            'category_id' => $accessories->id,
            'stock' => 5, // Low stock border
            'sku' => 'ACC-NOTE-006',
        ]);

        $chair = Product::create([
            'title' => 'Bauhaus Armchair',
            'description' => 'Classic tubular steel frame armchair with leather upholstery. Pure functional design.',
            'price' => 350.00,
            'image_url' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?w=500',
            'category_id' => $furniture->id,
            'stock' => 2, // Low stock alert
            'sku' => 'FRN-ARMH-007',
        ]);

        // 6. Seed sample reviews/ratings
        \App\Models\Review::create([
            'user_id' => $user->id,
            'product_id' => $lamp->id,
            'rating' => 5,
            'comment' => 'Incredible structure. The metallic matte paint is perfect and geometric form matches exactly.',
        ]);

        \App\Models\Review::create([
            'user_id' => $user->id,
            'product_id' => $chair->id,
            'rating' => 4,
            'comment' => 'Beautiful leather texture, and classic steel curves. A bit heavy to move, but gorgeous.',
        ]);

        \App\Models\Review::create([
            'user_id' => $user->id,
            'product_id' => $poster->id,
            'rating' => 5,
            'comment' => 'Clean design. Highly recommended for minimalist workspaces.',
        ]);
    }
}