<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

    }

    public function test_add_to_cart_product_repeat(): void
    {   
        $user =  User::factory()->create();
        $product = Product::factory()->create();
        
        Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/add-cart', [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Producto añadido al carrito correctamente'
            ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3
        ]);
    }
    public function test_add_to_cart(): void
    {
        $user =  User::factory()->create();

        $product = Product::factory()->create();
        

        $response = $this->actingAs($user)
            ->postJson('/api/add-cart', [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Producto añadido al carrito correctamente'
            ]);

        // Verificar que se haya creado el registro en la tabla de carrito
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);
    }

    public function test_view_cart()
    {
        $user =  User::factory()->create();

        Cart::factory(5)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/view-cart');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_update_cart_item()
    {   
        $this->withoutExceptionHandling();

        $user =  User::factory()->create();

        $product = Product::factory()->create();
        
        $cartItem = Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response = $this->actingAs($user)
            ->putJson('/api/update-cart/', [
                'product_id' => $product->id,
                'quantity' => 3
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'total_product_price' => $cartItem->products->price * 3,
            ])
            ->assertJsonStructure([
                'total_cart_price',
            ]);
    }

    public function test_remove_cart_item()
    {
        $user =  User::factory()->create();

        $product = Product::factory()->create();

        $cartItem = Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson('/api/remove-cart/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Producto eliminado correctamente del carrito'
            ]);

        // Verificar que el elemento de carrito haya sido eliminado de la base de datos
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }
}
