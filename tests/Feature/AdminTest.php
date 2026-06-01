<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHasAll([
            'totalRevenue',
            'ordersCount',
            'productsCount',
            'bestSellers',
            'recentOrders',
            'alertProducts',
        ]);
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat']);

        $productData = [
            'title'        => 'New Design Chair',
            'description'  => 'A premium geometric tubular steel minimalist chair.',
            'price'        => 299.99,
            'category_id'  => $category->id,
            'stock'        => 8,
            'sku'          => 'CHAIR-TEST-001',
            'material'     => 'Steel',
            'weight'       => '10kg',
            'dimensions'   => '50x50x80 cm',
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'New Design Chair',
            'sku'   => 'CHAIR-TEST-001',
            'stock' => 8,
        ]);
    }
}
