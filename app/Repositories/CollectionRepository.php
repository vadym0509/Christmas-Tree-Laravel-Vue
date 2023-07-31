<?php

namespace App\Repositories;

use App\Clients\Moltin;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;

class CollectionRepository
{
    /**
     * The Moltin client instance.
     *
     * @var \App\Clients\Moltin
     */
    protected $moltin;

    /**
     * Called when the class should load.
     *
     * @param \App\Clients\Moltin $moltin
     */
    public function __construct(Moltin $moltin)
    {
        $this->moltin = $moltin;
    }

    /**
     * Retrieves a collection by a given slug.
     *
     * @param string $slug
     * @return array|void
     */
    public function getBySlug(string $slug, $filter = true)
    {
        $collection = $this->moltin->getCollection($slug);

        if (!$filter) {
            return $collection;
        }

        // If filter param is passed, filter down based on subdomain.
        $filteredProducts = collect(data_get($collection, 'relationships.products'))->filter(function($product) {
            if (ff()) {
                return data_get($product, 'festive-floristry', false) == true;
            } else {
                return data_get($product, 'kilted-trees', false) == true;
            }
        });

        data_set($collection, 'relationships.products', $filteredProducts);

        return $collection;
    }
}
