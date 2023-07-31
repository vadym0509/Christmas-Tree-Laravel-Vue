<?php

/**
 * Menus
 */
Route::get('/top-sellers', 'Frontend\Api\MenuController@getTopSellers');

/**
 * Cart API
 */
Route::group(['middleware' => ['addfreeshipping']], function () {
    Route::get('/user/cart', 'Frontend\Api\CartController@getCart');
    Route::delete('/user/cart', 'Frontend\Api\CartController@destroyCart');
    Route::post('/user/cart/customItems', 'Frontend\Api\CartController@addCustomItemToCart');
    Route::post('/user/cart/items', 'Frontend\Api\CartController@addToCart');
    Route::put('/user/cart/items/{itemId}', 'Frontend\Api\CartController@updateCartItem');
    Route::delete('/user/cart/items/{itemId}', 'Frontend\Api\CartController@destroyCartItem');
});

/**
 * Checkout API
 */
Route::post('/user/checkout', 'Frontend\Api\CheckoutController@postCheckout');
Route::post('/user/checkout/{orderId}/complete', 'Frontend\Api\CheckoutController@postCheckoutComplete')->name('checkout-complete');
Route::post('/user/ff/checkout', 'Frontend\Api\CheckoutController@postFfCheckout');

/**
 * User API
 */
Route::post('/user/name', 'Frontend\Api\UserController@getUser');

/**
 * Promo code API
 */
Route::post('/promo-code/use', 'Frontend\Api\PromoCodeController@usePromoCode');

/**
 * Order API
 */
Route::post('/order/send-mails', 'Frontend\Api\OrderController@mailDataHandler');

/**
 * Product API
 */
Route::get('/product/{product}', 'Frontend\Api\ProductController@getProduct');

// Get sagePayMerchantSessionKey
Route::post('/cart/get-merchant-sessionkey', 'Frontend\CartController@getMerchantSessionKey');