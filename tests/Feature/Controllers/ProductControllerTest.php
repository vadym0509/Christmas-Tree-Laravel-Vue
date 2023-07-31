<?php

use App\Clients\Moltin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use GuzzleHttp\Exception\ClientException;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_show_aborts_with_404_if_no_product_is_found()
    {
        $response = $this->get(route('frontend.products.show', str_random(64)));

        $response->assertStatus(404);
    }

    public function test_show_injects_correct_variable()
    {
        // Requires product with slug 'test-product'
        // This can be resolved by using real fixture data once client provides this.

        $response = $this->get(route('frontend.products.show', 'test-product'));

        $response->assertViewHas('product', function ($product) {
            $this->assertTrue($product['slug'] === 'test-product');
        });
    }
}
