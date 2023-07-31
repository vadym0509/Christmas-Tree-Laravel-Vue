<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\ViewComponents\YouMayAlsoLike;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    /**
     * Shows a product.
     *
     * @param string $productSlug
     * @return \Illuminate\Http\Response
     */
    public function show($productSlug)
    {
        $shippingAndReturnsCopy = data_get(page('Shipping & Returns (Summary)'), 'body');

        abort_unless(
            $product = app(ProductRepository::class)->getBySlug($productSlug),
            404, 'Product not found'
        );

        abort_if(
            !ff() && (data_get($product, 'kilted-trees', false) == false),
            404,
            'Product is FF only'
        );

        $youMayAlsoLike = new YouMayAlsoLike([
            'context_data' => $product,
            'context_type' => 'product',
        ]);

        return view('frontend.products.show', compact('product', 'youMayAlsoLike', 'shippingAndReturnsCopy'));
    }
}
