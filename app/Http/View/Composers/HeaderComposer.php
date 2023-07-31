<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\CategoryRepository;
use App\Repositories\TopSellersRepository;

class HeaderComposer
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * The top sellers repository.
     *
     * @var App\Repositories\TopSellersRepository
     */
    protected $topSellersRepository;

    public function __construct(CategoryRepository $categoryRepository, TopSellersRepository $topSellersRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->topSellersRepository = $topSellersRepository;
    }
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @param CategoryRepository $categoryRepository
     * @return void
     */
    public function compose(View $view)
    {
        // TODO: Need to to figure out why adding a limit here doesnt work!
        $categories = $this->categoryRepository->getWithProducts()->convertToMenu();
        $topSellers = $this->topSellersRepository->getForMenu()->convertToMenu();

        $view->withCategories($categories)->withTopSellers($topSellers);
    }
}
