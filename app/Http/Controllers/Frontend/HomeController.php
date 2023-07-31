<?php

namespace App\Http\Controllers\Frontend;

use App\ViewComponents\About;
use App\ViewComponents\Banner;
use App\ViewComponents\Collection;
use App\Http\Controllers\Controller;
use App\ViewComponents\PromoTiles;

class HomeController extends Controller
{
    public function index()
    {
        $page = page('Home');

        $banner = new Banner([
            'image' => $page->additional_field('banner_image'),
            'icon' => $page->additional_field('banner_icon'),
            'header' => $page->additional_field('banner_header'),
            'cta_link' => $page->additional_field('banner_cta_link'),
            'cta_text' => $page->additional_field('banner_cta_text'),
            'body' => $page->additional_field('banner_body'),
        ]);

        $promo = PromoTiles::selectLatest();

        $collection = new Collection([
            'moltin_collection_key' => $page->additional_field('promotional_collection'),
            'subtitle' => $page->additional_field('promotional_collection_subtitle'),
        ]);

        // About component is rendered from a view component so we can share the data between all pages.

        return view('frontend.home.index', compact(
            'banner',
            'promo',
            'collection'
        ));
    }
}
