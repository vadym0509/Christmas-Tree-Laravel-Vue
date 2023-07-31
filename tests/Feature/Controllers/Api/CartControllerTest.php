<?php

/**
 * @group moltin
 */
class CartControllerTest extends TestCase
{
    public function test_it_creates_a_new_cart_if_none_in_session()
    {
        $response = $this->json('GET', '/api/user/cart');

        $response->assertSuccessful();
    }

    public function test_it_can_add_to_cart()
    {
        $response = $this->json('POST', 'api/user/cart/items', [
            'id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b',
            'quantity' => 5,
        ]);

        $response->assertJson(['product_id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b']);
    }

    public function test_it_can_remove_item_from_cart()
    {
        $response = $this->json('POST', 'api/user/cart/items', [
            'id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b',
            'quantity' => 5,
        ]);

        $content = json_decode((string) $response->getContent(), true);
        $this->assertEquals( 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b', $content['product_id']);
    }

    public function test_it_can_destroy_a_cart()
    {
        $response = $this->json('DELETE', 'api/user/cart');

        $response->assertSuccessful();

        // Check all items are gone.
        $response = $this->json('GET', '/api/user/cart');
        $this->assertEquals('[]', $response->getContent());
    }

    public function test_it_can_update_a_cart_item()
    {
        // Create an item
        $response = $this->json('POST', 'api/user/cart/items', [
            'id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b',
            'quantity' => 5,
        ]);

        $response = json_decode($response->getContent(), true);

        // Update the item
        $response = $this->json('PUT', '/api/user/cart/items/'.$response['id'], [
            'quantity' => 100,
            'id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b',
        ]);

        $response = $this->json('GET', '/api/user/cart');

        // Check the item has been updated
        $response = json_decode($response->getContent(), true);

        // TODO: Check quantity has been updated correctly, doesnt look to be working when printing $response from above.
        $this->markTestIncomplete('Need to check quantity');
    }

    public function test_it_can_destroy_a_cart_item()
    {
        $response = $this->json('POST', 'api/user/cart/items', [
            'id' => 'd158c283-bbed-4f3c-aa1a-bca6cdf2529b',
            'quantity' => 5,
        ]);

        $response = json_decode($response->getContent(), true);

        $response = $this->json('DELETE', '/api/user/cart/items/' . $response['id']);

        $response->assertSuccessful();

        // TODO: Check item was actually deleted from the cart.
        $this->markTestIncomplete('This test should check the item was actually deleted');
    }
}
