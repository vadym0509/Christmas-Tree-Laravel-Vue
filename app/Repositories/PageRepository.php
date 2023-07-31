<?php

namespace App\Repositories;

use Purcell\Hone\Models\Page;

class PageRepository
{
    /**
     * Gets a page by the given title.
     *
     * @param string $title
     * @return \Purcell\Hone\Models\Page
     */
    public function getByTitle(string $title): Page
    {
        abort_unless(
            $page = Page::where('title', $title)->first(),
            404, 'Page ['.$title.'] could not be located'
        );

        return $page;
    }

    /**
     * Gets a page by the given uri.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getByUri($uri)
    {
        return Page::where('uri', $uri)->first();
    }

    /**
     * Gets all pages which are information pages.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getInfoPages()
    {
        return Page::where('template', 'info')->get();
    }
}
