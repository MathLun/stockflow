<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/* Models */
use App\Models\Product;

class ProductApiTest extends TestCase
{
    /**
     * Traits
     **/
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * This test it can list products
     **/
    public function test_it_can_list_products(): void {
	    Product::factory()->count(3)->create();
	    $response = $this->getJson('/api/products');
	    $response->assertStatus(200);
	    $response->assertJsonCount(3);
    }

    public function test_it_can_create_a_product(): void
    {
	    $data = [
		'name' => 'Coca-cola 2L',
		'sku' => 'CC-2L-002',
		'description' => 'Refrigerante',
		'price' => 14.60,
		'active' => true
	    ];

	    $response = $this->postJson('/api/products', $data);
	    $response->assertCreated();

	    $this->assertDatabaseHas('products', ['sku' => 'CC-2L-002']);
	    $response->assertJsonFragment(['sku' => 'CC-2L-002']);
    }

    public function test_it_can_find_a_product_by_id(): void {
	    $product = Product::factory()->create();
	    $response = $this->getJson("/api/products/{$product->id}");

	    $response->assertOk();
	    $response->assertJsonFragment(['id' => $product->id, 'sku' => $product->sku]);
    }

    public function test_it_can_update_a_product(): void
    {
	    $product = Product::factory()->create();
	    $data = [
		'name' => 'Pepsi Cola 2L',
		'sku' => $product->sku,
		'description' => 'Refrescante',
		'price' => 16.90,
		'active' => true
	    ];

	    $response = $this->putJson("/api/products/{$product->id}", $data);
	    $response->assertOk();

	    $result = [
		'id' => $product->id,
		'name' => 'Pepsi Cola 2L',
		'price' => 16.90,
		'active' => true
	    ];

	    $this->assertDatabaseHas('products', $result);
	    $response->assertJsonFragment(['name' => 'Pepsi Cola 2L', 'price' => 16.90 ]);
    }

    public function test_it_can_delete_a_product(): void
    {
	    $product = Product::factory()->create();
	    $response = $this->deleteJson("/api/products/{$product->id}");

	    $response->assertNoContent();
	    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Error Test
     *
     * @test
     * should not allow creating two prod
     * ucts with the same SKU
     **/
     public function test_it_cannon_create_product_with_duplicate_sku(): void
     {
	     // Arrange
	     Product::factory()->create([
		     'sku' => 'SKU-001',
	     ]);

	     // Act
	     $response = $this->postJson("/api/products", [
		'name' => 'Other Product',
		'sku' => 'SKU-001',
		'description' => 'Produto duplicado',
		'price' => 20.00,
		'active' => true
	     ]);

	     // Assert
	     $response->assertStatus(422);
	     $response->assertJsonValidationErrors(['sku']);
     }

     public function test_it_cannot_find_a_non_existent_product(): void
     {
	     // Arrange
	     $nonExistentId = 999999;

	     // Act
	     $response = $this->getJson("/api/products/{$nonExistentId}");

	     // Assert
	     $response->assertNotFound();
     }

     public function test_it_cannot_update_a_non_existent_product(): void
     {
	     // Arrange
	     $nonExistentId = 999999;

	     $data = [
		'name' => 'Produto Atualizado',
		'sku' => 'SKU-999',
		'description' => 'Descrição atualizada',
		'price' => 99.90,
		'active' => true
	     ];

	     // Act
	     $response = $this->putJson("/api/products/{$nonExistentId}", $data);

	     // Assert
	     $response->assertNotFound();
     }

     public function test_it_cannot_delete_a_non_existent_product(): void
     {
	     // Arrange
	     $nonExistentId = 999999;

	     // Act
	     $response = $this->deleteJson("/api/products/{$nonExistentId}");

	     // Assert
	     $response->assertNotFound();
     }
}
