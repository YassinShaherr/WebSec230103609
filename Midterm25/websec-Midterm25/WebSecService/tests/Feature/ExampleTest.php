<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_create_product()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('products_save'), [
                'code' => 'PROD123',
                'name' => 'Test Product',
                'price' => 99.99,
                'model' => 'Model X',
                'description' => 'Test description'
            ]);
        
        $response->assertRedirect(route('products_list'));
        $this->assertDatabaseHas('products', ['code' => 'PROD123']);
    }
}
