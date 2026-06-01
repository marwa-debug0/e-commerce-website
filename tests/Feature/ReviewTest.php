<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_review()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title'       => 'Minimal Clock',
            'description' => 'Clean clock design.',
            'price'       => 99.00,
            'stock'       => 10,
            'sku'         => 'CLOCK-TEST-002',
        ]);

        $reviewData = [
            'rating'  => 5,
            'comment' => 'This matches my home office perfectly!',
        ];

        $response = $this->actingAs($user)->post(route('reviews.store', $product->id), $reviewData);

        $response->assertRedirect(route('product.show', $product->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'rating'     => 5,
            'comment'    => 'This matches my home office perfectly!',
        ]);

        // Verify ratings average updates
        $this->assertEquals(5.0, $product->fresh()->averageRating());
    }

    public function test_review_validation_enforces_rating_range()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title'       => 'Minimal Clock',
            'description' => 'Clean clock design.',
            'price'       => 99.00,
            'stock'       => 10,
            'sku'         => 'CLOCK-TEST-003',
        ]);

        $invalidData = [
            'rating'  => 6, // Exceeds max rating of 5
            'comment' => 'Great product!',
        ];

        $response = $this->actingAs($user)->post(route('reviews.store', $product->id), $invalidData);

        $response->assertSessionHasErrors(['rating']);
    }
}
