<?php

use Purcell\Hone\Models\Page;
use App\Repositories\PageRepository;

if (! function_exists('ff')) {
    /**
     * Checks if we're on the Festive Floristry domain.
     *
     * @return bool
     */
    function ff()
    {
        $route = request()->route();

        if ($route === null) {
            return '';
        }

        return (bool) starts_with($route->uri(), 'festive-floristry');
    }
}

if (! function_exists('page')) {
    /**
     * Gets a page by the given title.
     *
     * @param string $title
     * @return \Purcell\Hone\Models\Page
     */
    function page(string $title): Page
    {
        return app(PageRepository::class)->getByTitle($title);
    }
}
