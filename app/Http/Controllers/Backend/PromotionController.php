<?php

namespace App\Http\Controllers\Backend;

use App\Models\Promotion;
use Purcell\Hone\Models\Page;
use App\Http\Requests\StorePromotionRequest;
use Purcell\Hone\Http\Controllers\CrudController;

class PromotionController extends CrudController
{
    protected $model = Promotion::class;
    protected $store_request = StorePromotionRequest::class;
    protected $update_request = StorePromotionRequest::class;

    protected $routes_prefix = 'backend.promotions';
    protected $views_prefix = 'backend';

    public function delete($promotion)
    {
        $this->authorizeForUser($this->getUser(), __FUNCTION__, $promotion);

        if ($promotion->locked == 0) {
            return $promotion->delete();
        }

        return false;
    }
}
