<?php

namespace App\Repositories;

use App\Clients\Moltin;

class CartRepository
{
    /**
     * The Moltin Client instance.
     *
     * @var \App\Clients\Moltin
     */
    protected $moltin;

    /**
     * Called when the class should load.
     *
     * @param \App\Clients\Moltin $moltin
     */
    public function __construct(Moltin $moltin)
    {
        $this->moltin = $moltin;
    }

    /**
     * Creates a new cart instance.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        return $this->getById(data_get($data, 'id'));
    }

    /**
     * Gets a cart by a given identifier
     *
     * @param string $cartId
     * @return array
     */
    public function getById($cartId)
    {
        return $this->moltin->get("v2/carts/{$cartId}");
    }

    /**
     * Discards a cart.
     *
     * @param string $cartId
     * @return void
     */
    public function destroy($cartId)
    {
        $this->moltin->delete("v2/carts/{$cartId}");
    }

    /**
     * Gets a cart's items.
     *
     * @param string $cartId
     * @return array
     */
    public function getItems($cartId)
    {
        return $this->moltin->get("v2/carts/{$cartId}/items");
    }

    public function getCart($cartId)
    {
        return $this->moltin->get("v2/carts/{$cartId}");
    }

    /**
     * Stores an item in the cart.
     *
     * @param string $cartId
     * @param array $data
     * @param string $type
     * @return void
     */
    public function storeItem($cartId, $data, $type = 'cart_item')
    {
        return $this->moltin->post("v2/carts/{$cartId}/items", [
            'json' => [
                'data' => $data + ['type' => $type],
            ]
        ]);
    }

    /**
     * Updates an item in the cart
     *
     * @param string $cartId
     * @param string $cartItemId
     * @param array $data
     * @return array
     */
    public function updateItem($cartId, $cartItemId, $data)
    {
        return $this->moltin->put("v2/carts/{$cartId}/items/{$cartItemId}", array_merge($data, ['type' => 'cart_item']));
    }

    /**
     * Destroys a cart item
     *
     * @param string $cartId
     * @param string $cartItemId
     * @return array
     */
    public function destroyItem($cartId, $cartItemId)
    {
        return $this->moltin->delete("v2/carts/{$cartId}/items/{$cartItemId}");
    }

    /**
     * Get free_shipping from promotion id.
     */
    public function getFreeShipping($promotion_id=null){
        $free_shipping = $this->moltin->freeShipping($promotion_id);
        return $free_shipping;
    }

    /**
     * Use promo code in cart
     *
     * @param string $cartId
     * @param string $promoCode
     * @return array
     */
    public function useCartPromoCode($cartId, $promoCode)
    {
        $response = $this->moltin->post("v2/carts/{$cartId}/items", [
                'json' => [                'data' => [
                    'type' => 'promotion_item',
                    'code' => $promoCode['code']
                ]]
            ]
        );

        $promotion_id=data_get($response, "1.promotion_id");
        
        $free_shipping = $this->moltin->freeShipping($promotion_id);
        data_set($response, "1.free_shipping", $free_shipping);
        return $response;
    }
}
