<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CartRepository;

class AddFreeShipping
{

    protected $cartRepository;


    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    /**
     * Add free_shipping value to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $items=$response->original;

        //Add free_shipping if it doesn't exists in the promo item.
        foreach($items as $key=>$item){
            if (data_get($item, 'type') == "promotion_item") {
                $free_shipping = $this->cartRepository->getFreeShipping($item["promotion_id"]);
                data_set($item, "free_shipping", $free_shipping);
                $items[$key]=$item;
                return response()->json($items);
            }
        }
        return $response;
    }
}
