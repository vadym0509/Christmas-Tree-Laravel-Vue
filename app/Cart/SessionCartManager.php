<?php

namespace App\Cart;

use Webpatser\Uuid\Uuid;
use App\Exceptions\CartException;
use App\Repositories\CartRepository;
use GuzzleHttp\Exception\ClientException;
use App\Clients\Moltin;

class SessionCartManager
{
    /**
     * The cart repository instance.
     *
     * @var \App\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * @var App\Clients\Moltin
     */
    protected $moltin;

    /**
     * The Moltin cart ID key.
     *
     * @var string
     */
    protected $moltinCartIdKey = '';

    /**
     * Called when the class should load.
     *
     * @param \App\Repositories\CartRepository $cartRepository
     * @param Moltin $moltin
     */
    public function __construct(CartRepository $cartRepository, Moltin $moltin)
    {
        $this->cartRepository = $cartRepository;
        $this->moltin = $moltin;
        $this->setMoltinCartIdKey();
    }

    /*
     * Set moltin cart id key (depends on domain).
     */
    public function setMoltinCartIdKey(): void
    {
        $this->moltinCartIdKey = stripos(url()->previous(), 'festive-floristry')
            ? 'moltin_cart_id_ff'
            : 'moltin_cart_id_kc';
    }


    /**
     * Get cart with extra data
     *
     * @param $cart
     * @return \Illuminate\Support\Collection
     */
    protected function getFullCart($cart) {
        $cart = collect($cart);
        return $cart->map(function ($item) {
            $currentFullProduct = $this->moltin->getProduct($item['slug']);
            data_set($item, 'use_tuffnells', data_get($currentFullProduct, 'use_tuffnells', false));

            $deliveryCost = data_get($currentFullProduct, 'delivery_cost', false);
            if ($deliveryCost) {
                data_set($item, 'delivery_cost', $deliveryCost);
            }

            return $item;
        });
    }

    /**
     * Gets the current session cart.
     *
     * @return array
     * @throws CartException
     */
    public function getCart()
    {
        $cartId = $this->issueOrFetch();

        try {
            $cart = $this->cartRepository->getItems($cartId);
            return $this->getFullCart($cart);

        } catch (ClientException $exception) {
            throw new CartException('There was a problem loading the cart', $exception);
        }
    }

    /**
     * Adds an item to the cart.
     *
     * @param array $data
     * @return array
     * @throws CartException
     */
    public function addToCart($data)
    {
        $cartId = $this->issueOrFetch();

        try {
            $cart =  $this->cartRepository->storeItem($cartId, $data);
            return $this->getFullCart($cart);
        } catch (\Exception $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody());
            throw new CartException($response->errors[0]->detail, $exception);
        }
    }

    /**
     * Adds a custom item to the cart.
     *
     * @param array $data
     * @return array
     * @throws CartException
     */
    public function addCustomItemToCart($data)
    {
        $cartId = $this->issueOrFetch();

        try {
            return $this->cartRepository->storeItem($cartId, $data, 'custom_item');
        } catch (\Exception $exception) {
            throw new CartException('There was a problem to add the custom item to cart', $exception);
        }
    }

    /**
     * Updates a cart item
     *
     * @param string $cartItem
     * @param array $data
     * @return void
     * @throws CartException
     */
    public function updateCartItem($cartItem, $data)
    {
        if (is_null($cartId = $this->getCartIdFromSession())) {
            return;
        }

        try {
            $cart = $this->cartRepository->updateItem($cartId, $cartItem, $data);
            return $this->getFullCart($cart);
        } catch (ClientException $exception) {
            throw new CartException('There was a problem updating this cart item', $exception);
        }
    }

    /**
     * Destroys a cart item.
     *
     * @param string $cartItem
     * @return void
     */
    public function destroyCartItem($cartItem)
    {
        if (is_null($cartId = $this->getCartIdFromSession())) {
            return;
        }

        try {
            $cart = $this->cartRepository->destroyITem($cartId, $cartItem);
            return $this->getFullCart($cart);
        } catch (ClientException $exception) {
            throw new CartException('There was a problem updating the cart', $exception);
        }
    }

    /**
     * Destroys a current session cart.
     *
     * @return void
     */
    public function destroyCart()
    {
        // No cart associated to session so no need to remove anything.
        if (is_null($cartId = $this->getCartIdFromSession())) {
            return;
        }

        try {
            return $this->cartRepository->destroy($cartId);
        } catch (ClientException $exception) {
            throw new CartException('There was a problem deleting the cart', $exception);
        }
    }

    public function addCartPromo($promoCode)
    {
        if (is_null($cartId = $this->getCartIdFromSession())) {
            return;
        }

        try {
            return $this->cartRepository->useCartPromoCode($cartId, $promoCode);
        } catch (ClientException $exception) {
            throw new CartException('There was a problem to use promo code', $exception);
        }
    }

    /**
     * Gets the cart ID from the session
     *
     * @return string|null
     */
    public function getCartIdFromSession()
    {
        return request()->session()->get($this->moltinCartIdKey);
    }

    public function calculateTotal()
    {
        if (is_null($cartId = $this->getCartIdFromSession())) {
            return;
        }

        return data_get(
            $this->cartRepository->getCart($cartId),
            'meta.display_price.with_tax.amount'
        );
    }

    /**
     * Issues a new cart if none can be fetched
     *
     * @return string
     */
    protected function issueOrFetch()
    {
        $cartId = $this->getCartIdFromSession();

        if (is_null($cartId)) {
            request()->session()->put($this->moltinCartIdKey, $cartId = $this->issue());
        }

        return $cartId;
    }

    /**
     * Issues a new cart.
     *
     * @return boolean
     */
    protected function issue()
    {
        return tap($this->generateUuid(), function($uuid) {
            $this->cartRepository->create(['id' => $uuid]);
        });
    }

    /**
     * Generates a UUID.
     *
     * @return string
     */
    protected function generateUuid()
    {
        return Uuid::generate()->string;
    }
}
