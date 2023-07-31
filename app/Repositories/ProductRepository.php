<?php

namespace App\Repositories;

use App\Clients\Moltin;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;

class ProductRepository
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
    public function getBySlug(string $slug)
    {
        try {
            return $this->moltin->getProduct($slug);
        } catch (ClientException $exception) {
            Log::error($exception);
        }
    }
}
