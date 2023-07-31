<?php

namespace App\ViewComponents;

use App\Repositories\PromotionRepository;

class PromoTiles extends ViewComponent
{
    /**
     * The view file used to render the component.
     *
     * @var string
     */
    public $view = 'frontend.components.promo-tile';

    /**
     * Initalises the component selecting the latest data available.
     *
     * @return self
     */
    public static function selectLatest()
    {
        $promotions = app(PromotionRepository::class)->getLatest(99)->filter(function($promotion) {
            if (ff()) {
                return (bool) $promotion['festive-floristry'] == true;
            } else {
                return (bool) $promotion['kilted-trees'] == true;
            }
        });

        return new self(compact('promotions'));
    }
}
