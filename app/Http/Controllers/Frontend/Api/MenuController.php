<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TopSellersRepository;

class MenuController extends Controller
{
    /**
     * The top sellers repository.
     *
     * @var \App\Repositories\TopSellersRepository
     */
    protected $topSellersRepository;

    /**
     * Called when the class should load.
     *
     * @param \App\Repositories\TopSellersRepository $topSellersRepository
     */
    public function __construct(TopSellersRepository $topSellersRepository)
    {
        $this->topSellersRepository = $topSellersRepository;
    }

    /**
     * Gets top sellers.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTopSellers()
    {
        $menu = $this->topSellersRepository->getForMenu();

        return response()->json($menu);
    }
}
