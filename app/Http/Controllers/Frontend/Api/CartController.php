<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Cart\SessionCartManager;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * The session cart manager.
     *
     * @var \App\Cart\SessionCartManager
     */
    protected $sessionCartManager;

    /**
     * Called when the class should load.
     *
     * @param \App\Cart\SessionCartManager $sessionCartManager
     */
    public function __construct(SessionCartManager $sessionCartManager)
    {
        $this->sessionCartManager = $sessionCartManager;
    }

    /**
     * Gets the current session cart items.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCart()
    {
        $cart = $this->sessionCartManager->getCart();

        return response()->json($cart);
    }

    /**
     * Destroys the current session cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyCart()
    {
        $this->sessionCartManager->destroyCart();

        return response()->json();
    }

    /**
     * Adds an item to the cart
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CartException
     */
    public function addToCart()
    {
        request()->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer',
        ]);

        $cartItems = $this->sessionCartManager->addToCart(
            request()->input()
        );

        return response()->json($cartItems);
    }

    /**
     * Adds a custom item to the cart
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CartException
     */
    public function addCustomItemToCart()
    {
        $cartItems = $this->sessionCartManager->addCustomItemToCart(
            request()->input()
        );

        return response()->json($cartItems);
    }

    /**
     * Updates a cart item.
     *
     * @param string $cartItem
     * @return \Illuminate\Http\Response
     */
    public function updateCartItem($cartItem)
    {
        request()->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer',
        ]);

        $cartItems = $this->sessionCartManager->updateCartItem($cartItem, request()->input());

        return response()->json($cartItems);
    }

    /**
     * Destroys a cart item
     *
     * @param string $cartItem
     * @return \Illuminate\Http\Response
     */
    public function destroyCartItem($cartItem)
    {
        $cartItems = $this->sessionCartManager->destroyCartItem($cartItem);

        return response()->json($cartItems);
    }
}
