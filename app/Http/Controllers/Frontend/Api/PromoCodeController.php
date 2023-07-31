<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Cart\SessionCartManager;
use App\Http\Controllers\Controller;

class PromoCodeController extends Controller
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
     * Use promocode
     *
     * @return \Illuminate\Http\Response
     */
    public function usePromoCode()
    {
        request()->validate([
            'code' => 'required|string',
        ]);
        $cartItems = $this->sessionCartManager->addCartPromo(
            request()->input()
        );
        return response()->json($cartItems);
    }
}
