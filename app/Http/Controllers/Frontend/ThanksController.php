<?php

namespace App\Http\Controllers\Frontend;

use App\ViewComponents\Banner;
use App\Http\Controllers\Controller;

class ThanksController extends Controller
{
    public function kt()
    {
        // Use home page banner.
        $page = page('Home');

        $banner = new Banner([
            'image' => $page->additional_field('banner_image'),
            'icon' => $page->additional_field('banner_icon'),
            'header' => $page->additional_field('banner_header'),
            'cta_link' => $page->additional_field('banner_cta_link'),
            'cta_text' => $page->additional_field('banner_cta_text'),
            'body' => $page->additional_field('banner_body'),
        ]);

        return view('frontend.thanks.kt', compact('banner'));
    }

    public function ff()
    {
        // Use home page banner.
        $page = page('FF Home');

        $banner = new Banner([
            'image' => $page->additional_field('banner_image'),
            'icon' => $page->additional_field('banner_icon'),
            'header' => $page->additional_field('banner_header'),
            'cta_link' => $page->additional_field('banner_cta_link'),
            'cta_text' => $page->additional_field('banner_cta_text'),
            'body' => $page->additional_field('banner_body'),
        ]);

        return view('frontend.thanks.ff', compact('banner'));
    }
}
