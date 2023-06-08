<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private $route_uri;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            'Database\\Seeders\\BrandSeeder',
            'Database\\Seeders\\CategorySeeder',
            'Database\\Seeders\\SubcategorySeeder',
        ]);
        $this->route_uri = 'api/products';
    }

    protected function data($overrides = [])
    {
        $defaultData = [
            'name' => 'celular',
            'slug' => 'slug default',
            'description' => 'descripcion del celular',
            'price' => "10",
            'stock' => "10",
            'status' =>  'NEW',
            'measures' =>  json_encode(['altura' => 10, 'ancho' => 20 , 'largo' => 30]),
            'brand_id' => 1,
            'subcategory_id' => 1,
            'image' => [
                UploadedFile::fake()->image('post1.jpg'),
                UploadedFile::fake()->image('post2.jpg'),
                UploadedFile::fake()->image('post3.jpg'),
            ],
        ];

        return array_merge($defaultData, $overrides);
    }

    /** @test */
    public function create_product()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');

        $this->post($this->route_uri, $this->data())->assertStatus(200)->assertJson(['msg' => "Se ha creado satisfactoriamente."]);

        $urls = Product::first()->images->pluck('url');

        foreach ($urls as $url) {
            Storage::disk('public')->assertExists('/img/products/' . $url);
        }

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('images', 3);
    }

    /** @test */
    public function update_the_product()
    {
        Storage::fake('public');

        $this->post($this->route_uri, $this->data())->assertStatus(200)->assertJson(['msg' => "Se ha creado satisfactoriamente."]);


        $product = Product::first();

        $urls = Product::first()->images->pluck('url');

        foreach ($urls as $url) {
            Storage::disk('public')->assertExists('/img/products/' . $url);
        }

        // Verificar que la imagen del producto exista en el almacenamiento
        $this->post($this->route_uri . "/" . $product->id, $this->data(['name' => 'celular actualizado']))->assertStatus(200)->assertJson(['msg' => "Se ha actualizado satisfactoriamente."]);

        $urls = Product::first()->images->pluck('url');

        // Verificar que la imagen del producto exista en el almacenamiento
        foreach ($urls as $url) {
            Storage::disk('public')->assertExists('/img/products/' . $url);
        }

        $this->assertDatabaseCount('images', 3);

        $this->assertDatabaseHas(
            'products',
            [
                'name' => 'celular actualizado'
            ]
        );
    }

    /** @test */
    public function remove_the_product()
    {
        Storage::fake('public');

        $this->post($this->route_uri, $this->data())->assertStatus(200)->assertJson(['msg' => "Se ha creado satisfactoriamente."]);

        $product = Product::first();

        $urls = Product::first()->images->pluck('url');

        $this->delete($this->route_uri . "/" . $product->id)->assertStatus(200)->assertJson(['msg' => "Se ha eliminado satisfactoriamente."]);;

        foreach ($urls as $url) {
            Storage::disk('public')->assertMissing('/img/products/' . $url);
        }

        foreach ($urls as $url) {
            $this->assertDatabaseMissing('images', ['url' => $url]);
        }
    }
}
