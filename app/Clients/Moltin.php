<?php

namespace App\Clients;

use Illuminate\Support\Arr;
use MatthewErskine\Guzzle\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;

class Moltin extends Client
{
    use MoltinAuthentication;

    /**
     * Called when the class should load.
     *
     * @param \Illuminate\Redis\RedisManager $redis
     * @param string $url
     * @param \GuzzleHttp\Client $httpClient
     * @param array $guzzle
     */
    public function __construct($redis, $url, GuzzleClient $httpClient, $guzzle = [])
    {
        $this->redis = $redis;

        parent::__construct($url, $httpClient, $guzzle);
    }

    /**
     * Performs a heartbeat of the service.
     *
     * @return boolean
     */
    public function heartbeat()
    {
        return $this->getHttpClient()->get($this->getUrl())->getStatusCode() === 200;
    }

    /**
     * Performs a GET operation.
     *
     * @param string $uri
     * @param bool $unwrap
     * @return void
     */
    public function get($uri, $unwrap = true)
    {
        return $this->respond(
            $this->getHttpClient()->get(
                $this->getUrl() . $uri,
                $this->getGuzzle()
            ), $unwrap
        );
    }

    /**
     * Performs a GET request with full response info available (including metadata).
     *
     * @param string $uri
     * @return void
     */
    public function getUnwraped($uri)
    {
        return $this->get($uri, false);
    }

    /**
     * Gets a Moltin collection with nested products and product images.
     *
     * @param string $slug
     * @return array
     */
    public function getCollection(string $slug)
    {
        if ($cached = Cache::get((ff() ? 'ff:' : 'kt:') . "moltin:collection-slug:$slug")) {
            return $cached;
        }

        $response = $this->getUnwraped(
            "v2/collections?include=products,main_images&filter=eq(slug,{$slug})"
        );

        $collection = data_get($response, 'data.0');

        if (empty($collection)) {
            return null;
        }

        // Nest products in
        data_set($collection, 'relationships.products', data_get($response, 'included.products', []));

        // Nest images in
        foreach (data_get($collection, 'relationships.products') as $key => $product) {
            $file = $this->get('v2/files/'.data_get($product, 'relationships.main_image.data.id'));

            // Repull the product, since for some reasons flows aren't included with the "include" param on moltin API :(
            data_set($collection, "relationships.products.{$key}", $this->get('v2/products/' . $product['id']));

            data_set($collection, "relationships.products.{$key}.relationships.main_image", $file);
        }

        Cache::put((ff() ? 'ff:' : 'kt:') . "moltin:collection-slug:$slug", $collection);

        return $collection;
    }

    /**
     * Gets a Moltin product with nested images
     *
     * @param string $id
     * @return array
     */
    public function getProductById(string $id)
    {
        if ($cachedProduct = Cache::get((ff() ? 'ff:' : 'kt:') . "moltin:product:$id")) {
            return $cachedProduct;
        }

        $response = $this->getUnwraped("v2/products/{$id}?include=main_images,categories,files, childrens");

        $product = data_get($response, 'data');

        if (empty($product)) {
            return null;
        }

        if (data_get($product, 'relationships.categories')) {
            data_set($product, "relationships.categories", data_get($response, 'included.categories'));
        }
        if (data_get($product, 'relationships.main_image')) {
            data_set($product, "relationships.main_image", data_get($response, 'included.main_images.0'));
        }
        if (data_get($product, 'relationships.files')) {
            data_set($product, "relationships.files", data_get($response, 'included.files'));
        }
        if (data_get($product, 'relationships.children')) {
            data_set($product, 'relationships.children', data_get($response, 'included.childrens'));
        }

        Cache::put((ff() ? 'ff:' : 'kt:') . "moltin:product:$id", $product);

        return $product;
    }


    public function freeShipping($promotion_id){
        $data = $this->getUnwraped("v2/promotions/{$promotion_id}");
        $free_shipping=data_get($data, 'data.free_shipping');
        return $free_shipping;
    }

    /**
     * Gets a Moltin product with nested images
     *
     * @param string $slug
     * @return array
     */
    public function getProduct(string $slug)
    {
        if ($cachedProduct = Cache::get((ff() ? 'ff:' : 'kt:') . "moltin:product-slug:$slug")) {
            return $cachedProduct;
        }

        $getProduct = $this->getUnwraped("v2/products?filter=eq(slug,{$slug})");

        $getProductId = data_get($getProduct, 'data.0.id');
        $response = $this->getUnwraped("v2/products/{$getProductId}?include=main_images,categories,files, childrens");

        $product = data_get($response, 'data');

        if (empty($product)) {
            return null;
        }

        $inventory = $this->getUnwraped("v2/inventories/$getProductId");
        if ($inventory) {
            $filtered = Arr::except($inventory, ['data.id', 'data.type']);
            data_set($product, 'meta.stock', data_get($filtered, 'data'));
        }

        if (data_get($product, 'relationships.categories')) {
            data_set($product, 'relationships.categories', data_get($response, 'included.categories'));
        }
        if (data_get($product, 'relationships.main_image')) {
            data_set($product, 'relationships.main_image', data_get($response, 'included.main_images.0'));
        }
        if (data_get($product, 'relationships.files')) {
            data_set($product, 'relationships.files', data_get($response, 'included.files'));
        }
        if (data_get($product, 'relationships.children')) {
            data_set($product, 'relationships.children', data_get($response, 'included.childrens'));
        }

        Cache::put((ff() ? 'ff:' : 'kt:') . "moltin:product-slug:$slug", $product);

        return $product;
    }

    /**
     * Gets a Moltin categories with products VERY EXPENSIVE!!
     *
     * @return array
     */
    public function getCategories($limit = null)
    {
        if ($cached = Cache::get((ff() ? 'ff:' : 'kt:') . "moltin:categories")) {
            return $cached;
        }

        if (empty($categories = data_get($this->getUnwraped("v2/categories?sort=created_at"), 'data'))) {
            return null;
        }

        // Load products (expensive)
        foreach ($categories as $keyCategory => $category) {
            if (data_get($category, 'relationships.products')) {
                if (is_int($limit)) {
                    data_set($category, 'relationships.products.data', collect(data_get($category, 'relationships.products.data'))->take($limit));
                }

                // Remove if status is draft from category.
                $allproducts=data_get($category, 'relationships.products.data');
                $products=[];
                foreach($allproducts as $key=>$prod){
                    if($this->getProductById(data_get($prod, 'id'))['status']=="live"){
                        array_push($products, $prod);
                    }
                }

                data_set($categories, $keyCategory.'.relationships.products.data', array());

                $index=0;
                foreach ($allproducts as $productCategoryKey => $productCategory) {
                    $productId = data_get($productCategory, 'id');
                    $pulledProduct = $this->getProductById($productId);
                    if($pulledProduct['status']=="live"){
                        data_set($categories, $keyCategory.'.relationships.products.data.'.$index, $pulledProduct);
                        $index++;
                    }
                }
            }
        }

        Cache::put((ff() ? 'ff:' : 'kt:') . "moltin:categories", $categories);

        return $categories;
    }

    /**
     * Gets a Moltin category with products
     *
     * @param string $slug
     * @return array
     */
    public function getCategory(string $slug)
    {
        if ($cached = Cache::get((ff() ? 'ff:' : 'kt:') . "moltin:categories-slug:$slug")) {
            return $cached;
        }

        $response = $this->getUnwraped("v2/categories?filter=eq(slug,{$slug})");

        $category = data_get($response, 'data.0');

        if (empty($category)) {
            return null;
        }

        // Nest product data
        if (data_get($category, 'relationships.products')) {
            $category['relationships']['products']['data'] = collect(data_get($category, 'relationships.products.data'))->transform(function($product) {
                return $this->getProductById(data_get($product, 'id'));
            })->reject(function($product) {
                return data_get($product, 'status', 'draft') == 'draft';
            })->reject(function($product) {
                if (ff()) {
                    return data_get($product, 'festive-floristry', false) == false;
                }

                if (! ff()) {
                    return data_get($product, 'kilted-trees', false) == false;
                }
            })->toArray();
        }

        Cache::put((ff() ? 'ff:' : 'kt:') . "moltin:categories-slug:$slug", $category);

        return $category;
    }

    /**
     * Perfiorms a POST operation.
     *
     * @param string $uri
     * @param array $body
     * @return void
     */
    public function post($uri, $body = [])
    {
        return $this->respond(
            $this->getHttpClient()->post(
                $this->getUrl() . $uri,
                $this->getGuzzle() + $body
            )
        );
    }

    /**
     * Performs a PUT operation.
     *
     * @param string $uri
     * @param array $data
     * @return void
     */
    public function put($uri, $data = [])
    {
        return $this->respond(
            $this->getHttpClient()->put(
                $this->getUrl() . $uri,
                $this->getGuzzle() + [
                    'json' => ['data' => $data],
                ]
            )
        );
    }

    /**
     * Performs a DELETE operation.
     *
     * @return void
     */
    public function delete($uri)
    {
        return $this->respond(
            $this->getHttpClient()->delete(
                $this->getUrl() . $uri,
                $this->getGuzzle()
            )
        );
    }

    /**
     * Retrieves content from the response of a request.
     *
     * @param  \GuzzleHttp\Psr7\Response $response
     * @param  bool $unwrap
     * @return string
     */
    public function respond($response, $unwrap = true)
    {
        $this->setLastResponse($response);

        $response = json_decode(
            $response->getBody()->getContents(),
            true
        ) ?? (string)$response->getBody();

        if ($unwrap) {
            return data_get($response, 'data', $response);
        }

        return $response;
    }
}
