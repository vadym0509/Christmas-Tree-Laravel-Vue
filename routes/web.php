<?php

use App\Cart\SessionCartManager;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('/thanks-for-order', 'Frontend\ThanksController@kt')->name('frontend.thanks');
Route::get('/products/{slug}', 'Frontend\ProductController@show')->name('frontend.products.show');
Route::get('/categories/{slug}', 'Frontend\CategoryController@show')->name('frontend.categories.show');


Route::group(['prefix' => 'festive-floristry'], function() {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('frontend.ff.login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/forgot-password', 'Auth\LoginController@showForgotPasswordForm')->name('frontend.ff.forgot-password');
    Route::post('/forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('/reset-password', 'Auth\ResetPasswordController@showResetForm')->name('member.showresetpasswordform');
    Route::post('/reset-password', 'Auth\ResetPasswordController@reset');
    Route::post('/register', 'Auth\RegisterController@register');
});

Route::group(['prefix' => 'festive-floristry', 'middleware' => ['ffauth']], function() {
    Route::get('/', 'Frontend\FF\HomeController@index')->name('festive-floristry');
    Route::get('/logout', 'Auth\LoginController@logout');

    Route::get('/products/{slug}', 'Frontend\ProductController@show')->name('frontend.ff.products.show');
    Route::get('/categories/{slug}', 'Frontend\CategoryController@show')->name('frontend.ff.categories.show');
    Route::get('/cart', 'Frontend\CartController@index')->name('frontend.cart');
    Route::get('/thanks-for-order', 'Frontend\ThanksController@ff')->name('frontend.ff.thanks');
    Route::get('/{slug}', 'Frontend\InfoController@showPage')->name('frontend.ff.info.show');
});

Route::get('/category/{slug}', 'Frontend\CategoryController@show')
    ->name('frontend.category.show');

Route::get('/cart', 'Frontend\CartController@index')
    ->name('frontend.cart');

/**
 * Backend routes
 */

Route::group(['prefix' => config('project_settings.admin_subdirectory', 'admin'), 'middleware' => ['blocker']], function () {
    Route::post('/promotions/mass', 'Backend\PromotionController@massDelete')->name('backend.promotions.mass_delete');

    Route::resource( '/categories', 'Backend\CategoryController')->names([
        'index' => 'backend.categories.index',
        'show' => 'backend.categories.show',
        'edit' => 'backend.categories.edit',
        'update' => 'backend.categories.update',
    ]);

    Route::resource('/promotions', 'Backend\PromotionController')->names([
        'index' => 'backend.promotions.index',
        'create' => 'backend.promotions.create',
        'edit' => 'backend.promotions.edit',
        'update' => 'backend.promotions.update',
        'store' => 'backend.promotions.store',
        'destroy' => 'backend.promotions.destroy',
    ]);
});

Route::get('/{slug}', 'Frontend\InfoController@showPage')->name('frontend.info.show');

