<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Clients\Moltin;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * The Moltin client instance.
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
     * Gets the Product by ID.
     *
     * @param $product
     * @return JsonResponse
     */
    public function getProduct($product)
    {
        $product = $this->moltin->getProductById($product);

        return response()->json($product);
    }

}
