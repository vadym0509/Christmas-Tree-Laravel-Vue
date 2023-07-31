<?php

namespace App\ViewComponents;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\CollectionRepository;
use App\Exceptions\ViewComponentRenderException;

class Collection extends ViewComponent
{
    /**
     * The view file used to render the component.
     *
     * @var string
     */
    public $view = 'frontend.components.collection';

    /**
     * Gets a collection repository instance.
     *
     * @return \App\Repositories\CollectionRepository
     */
    public function getCollectionRepository()
    {
        return app(CollectionRepository::class);
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
        if (is_null($this->moltin_collection_key)) {
            throw new ViewComponentRenderException(
                'No product collection has been set for this collection.'
            );
        }

        // We'll attempt to fetch the collection from Moltin. If any errors occur during the
        // fetch process, we can simply re-throw this as a view component rendering issue
        // so that the application doesn't die. We can handle correctly on the frontend.

        try {
            $this->data['collection'] = $this->getCollectionRepository()->getBySlug(
                $this->moltin_collection_key
            );
        }
        catch (ClientException $exception) {
            Log::error($exception);

            $collectionKey = $this->moltin_collection_key;

            throw new ViewComponentRenderException(
                "There was an issue loading this collection [{$collectionKey}] on Moltin"
            );
        }
    }
}
