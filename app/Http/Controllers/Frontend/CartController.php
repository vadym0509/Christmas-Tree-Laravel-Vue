<?php

namespace App\Http\Controllers\Frontend;

use App\Cart\SessionCartManager;
use App\Clients\SagePay;
use App\ViewComponents\Collection;
use App\Http\Controllers\Controller;
use App\ViewComponents\YouMayAlsoLike;

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

    public function index()
    {
        $cartCopy = data_get(page('Cart (Summary)'), 'body');

        $youMayAlsoLike = new YouMayAlsoLike([
            'context_type' => 'checkout',
            'context_data' => $this->sessionCartManager->getCart(),
        ]);

        $sagePayMerchantSessionKey = app(SagePay::class)->getMerchantSessionKey();

        return view('frontend.cart.index', compact('youMayAlsoLike', 'sagePayMerchantSessionKey', 'cartCopy'));
    }

    public function getMerchantSessionKey(){
        $sagePayMerchantSessionKey = app(SagePay::class)->getMerchantSessionKey();
        return response()->json($sagePayMerchantSessionKey);
    }
}
