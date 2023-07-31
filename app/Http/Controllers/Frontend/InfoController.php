<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Str;
use App\ViewComponents\Banner;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Route;

class InfoController extends Controller
{
    /**
     * The page repository instance.
     *
     * @var \App\Repositories\PageRepository
     */
    protected $pageRepository;

    /**
     * Called when the class should load.
     *
     * @param \App\Repositories\PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Show the current page.
     *
     * @param string $uri
     * @return void
     */
    public function showPage($uri)
    {
        abort_unless(
            $page = $this->pageRepository->getByUri($uri),
            404, 'Page not found'
        );

        $banner = new Banner([
            'image' => $page->additional_field('banner_image'),
            'icon' => null, // Tree icon will go here.
            'header' => $page->title,
        ]);

        $prefix = ff() ? '/festive-floristry/' : '/';

        $infoMenu = $this->pageRepository->getInfoPages()->transform(function($menuPage) use ($page, $prefix) {
            return (object) [
                'isActive' => $menuPage->id === $page->id,
                'url' => Str::start($menuPage->uri, $prefix),
                'title' => $menuPage->title
            ];
        });

        return view('frontend.info.show', compact('banner', 'infoMenu', 'page'));
    }
}
