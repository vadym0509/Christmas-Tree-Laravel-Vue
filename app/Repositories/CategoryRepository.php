<?php

namespace App\Repositories;

use App\Clients\Moltin;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;

class CategoryRepository
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

    public function getMenuFooterItems()
    {
        return collect($this->moltin->get("v2/categories"))->filter(function ($category) {
            // Filter category by sub-domain.
            if (!ff()) {
                return data_get($category, 'kilted-trees', false) === true;
            } elseif (ff()) {
                return data_get($category, 'festive-floristry', false) === true;
            }

            return false;
        })->transform(function ($category) {
            $route = ff() ? 'frontend.ff.categories.show' : 'frontend.categories.show';
            return (object) [
                'url' => route($route, $category['slug']),
                'title' => $category['name'],
            ];
        });
    }

    /**
     * Retrieves all categories with products.
     *
     * @param null $limit
     * @return array|null
     */
    public function getWithProducts($limit = null)
    {
        return collect($this->moltin->getCategories($limit))->transform(function ($category) {
            $products = collect(data_get($category, 'relationships.products.data', []))->filter(function ($product) {
                if (data_get($product, 'kilted-trees', false) === !ff()) {
                    return true;
                }
                elseif (data_get($product, 'festive-floristry', false) === ff()) {
                    return true;
                }
            });

            //order products by field "Order"
            $sortedProducts = $products->sortBy('order');

            data_set($category, 'relationships.products.data', $sortedProducts);

            return $category;
        })->filter(function ($category) {
            // Filter category by sub-domain.
            if (!ff()) {
                return data_get($category, 'kilted-trees', false) === true;
            } elseif (ff()) {
                return data_get($category, 'festive-floristry', false) === true;
            }

            return false;
        });
    }

    /**
     * Retrieves a category by a given slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function getBySlug(string $slug)
    {
        return $this->moltin->getCategory($slug);
    }

    /**
     * Gets a paginated category collection.
     *
     * @return \LengthAwarePaginator
     */
    public function getPaginated()
    {
        return collect($this->moltin->get('v2/categories'))->paginate();
    }

    /**
     * Gets a category by a given identifier.
     *
     * @param string $id
     * @return array
     */
    public function getById($id)
    {
        return $this->moltin->get('v2/categories/'.$id);
    }

    /**
     * Updates a category.
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update($id, $data)
    {
        $this->moltin->put('v2/categories/'.$id, $data);
    }
}
