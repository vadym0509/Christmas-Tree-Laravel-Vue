<?php

namespace App\Repositories;

use App\Clients\Moltin;
use App\Exceptions\MoltinConfigurationException;

class TopSellersRepository
{
    /**
     * The Moltin Client instance.
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

    public function getForMenu()
    {
        // Get all top seller categories (need to manually filter as eq() not working for flows in Moltin... not sure how to do it)
        $categories = collect($this->moltin->get('v2/categories'))

            // Filter any categories out that are not top seller
            ->filter(function ($category) {
                if (! array_key_exists('top-seller', $category)) {
                    throw new MoltinConfigurationException(
                        'No `top-seller` flow has been created for categories.'
                    );
                }

                return $category['top-seller'] === true;
            })

            // Go through each product and load the full product in
            ->transform(function($category) {
                $transformedProducts = collect(data_get($category, 'relationships.products.data', []))->transform(function($item) {
                    return $this->moltin->get('v2/products/'.$item['id']);
                })

                    // Filter any products that are not top seller
                    ->filter(function ($product) {
                        if (! array_key_exists('top-seller', $product)) {
                            throw new MoltinConfigurationException(
                                'No `top-seller` flow has been created for products.'
                            );
                        }

                        return $product['top-seller'] === true;
                    })

                    // Filter based on KT
                    ->filter(function($product) {
                        if (ff()) {
                            return data_get($product, 'festive-floristry', false) == true;
                        } else {
                            return data_get($product, 'kilted-trees', false) == true;
                        }
                    })
                    ->toArray();

                return data_set($category, 'relationships.products.data', $transformedProducts);
            });

        // Filter any empty collections out
        // We now have a data set containing all top seller categories containing at least 1 top seller product
        return $categories->filter(function($category) {
            return !empty(data_get($category, 'relationships.products.data'));
        });
    }
}
