<?php

namespace App\ViewComponents;

use BadMethodCallException;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use App\Repositories\CategoryRepository;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\CollectionRepository;
use App\Exceptions\ViewComponentRenderException;

class YouMayAlsoLike extends ViewComponent
{
    /**
     * The view file used to render the component.
     *
     * @var string
     */
    public $view = 'frontend.components.you-may-also-like';

    /**
     * Gets a category repository instance.
     *
     * @return \App\Repositories\CategoryRepository
     */
    public function getCategoryRepository()
    {
        return app(CategoryRepository::class);
    }

    /**
     * Gets the data which should be used to render the component's markup.
     *
     * @return array
     */
    public function getViewData()
    {
        // When the view is required to be compiled we will fetch the data
        // from Moltin and populate this component before rendering.

        $this->populate();

        return parent::getViewData();
    }

    /**
     * Grabs products from the collection.
     *
     * @return array
     */
    public function getProducts()
    {
        return data_get($this->collection, 'relationships.products.data', []);
    }

    /**
     * Populates the view component with data from Moltin.
     *
     * @return void
     */
    public function populate()
    {
        // The context of this component will determine which types of items should appear
        // within the "You May Also Like" suggestions, for example, if our customer is
        // viewing a product, we might want to relate products in other categories.

        // $this->data['hidden'] = true;

        try {
            $this->{'populateFor'.$this->data['context_type']}();
        } catch (BadMethodCallException $exception) {
            Log::error($exception);

            throw new ViewComponentRenderException(sprintf(
                "No context function has been defined in the You May Also Like component for the [%s] context type",
                $this->data['context_type']
            ));
        } catch (ClientException $exception) {
            throw new ViewComponentRenderException(
                "There was an issue loading data from Moltin"
            );
        }
    }

    /**
     * Populates data in the context of category.
     *
     * @return void
     */
    protected function populateForCategory()
    {
        // For a category, we want to select products from other categories.

        try {

            // Retrieve some products by removing the current category and any categories
            // with no products available within that set. We can then select 4 random
            // elements to populate our You May Also Like data in the frontend.

            $products = $this->getCategoryRepository()->getWithProducts()->reject(function ($category) {
                return data_get($category, 'id') == data_get($this->data['context_data'], 'id');
            })->reject(function ($category) {
                return data_get($category, 'relationships.products.data')->isEmpty();
            })->pluck('relationships.products.data')->reduce(function($arr, $item) {
                $arr = $arr ?: collect();
                return $arr->merge($item);
            })->shuffle()->random(4);

            $this->data['products'] = $products;
        } catch (InvalidArgumentException $exception) {
            Log::error($exception);
            $this->data['hidden'] = true;
        }
    }

    /**
     * Populates data in the context of a product.
     *
     * @return void
     */
    protected function populateForProduct()
    {
        // For a product, we want to select products from other categories.

        try {

            // Retrieve some products by removing the current category and any categories
            // with no products available within that set. We can then select 4 random
            // elements to populate our You May Also Like data in the frontend.

            $products = $this->getCategoryRepository()->getWithProducts()->reject(function ($category) {
                return data_get($category, 'id') == data_get($this->data['context_data'], 'relationships.categories.0');
            })->reject(function ($category) {
                return data_get($category, 'relationships.products.data')->isEmpty();
            })->pluck('relationships.products.data')->reduce(function($arr, $item) {
                $arr = $arr ?: collect();
                return $arr->merge($item);
            })->shuffle()->random(4);

            $this->data['products'] = $products;
        } catch (InvalidArgumentException $exception) {
            Log::error($exception);
            $this->data['hidden'] = true;
        }

    }

    /**
     * Populates data in the context of checkout.
     *
     * @return void
     */
    protected function populateForCheckout()
    {
        // For a checkout, if one product select from other categories, otherwise we'll randomise it.

        try {

            // Retrieve some products by removing the current category and any categories
            // with no products available within that set. We can then select 4 random
            // elements to populate our You May Also Like data in the frontend.

            $addedToCartProductIds = collect($this->data['context_data'])->pluck('product_id')->toArray();

            $products = $this->getCategoryRepository()->getWithProducts()->reject(function ($category) {
                return data_get($category, 'relationships.products.data')->isEmpty();
            })->pluck('relationships.products.data')->reduce(function ($arr, $item) {
                $arr = $arr ?: collect();
                return $arr->merge($item);
            })->reject(function($product) use($addedToCartProductIds) {
                return in_array($product['id'], $addedToCartProductIds);
            })->shuffle()->random(4);

            $this->data['products'] = $products;
        } catch (InvalidArgumentException $exception) {
            Log::error($exception);
            $this->data['hidden'] = true;
        }
    }
}
