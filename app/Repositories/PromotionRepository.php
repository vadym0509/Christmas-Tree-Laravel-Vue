<?php

namespace App\Repositories;

use App\Models\Promotion;

class PromotionRepository
{
    /**
     * Gets the latest promotions
     *
     * @param integer $count
     * @return void
     */
    public function getLatest($count = 4)
    {
        return Promotion::limit($count)->orderBy('id', 'desc')->get();
    }
}
