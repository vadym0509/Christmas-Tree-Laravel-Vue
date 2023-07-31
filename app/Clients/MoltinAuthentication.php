<?php

namespace App\Clients;

trait MoltinAuthentication
{
    /**
     * The cached access token key.
     *
     * @var string
     */
    public static $cachedAccessTokenKey = 'moltin_access_token';

    /**
     * Authenticates with the Moltin service.
     *
     * @return void
     */
    public function authenticate()
    {
        if ($accessToken = $this->getCachedAccessToken()) {
            return $accessToken;
        }

        $response = $this->respond(
            $this->getHttpClient()->post(
                $this->getUrl() . 'oauth/access_token',
                [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => env('MOLTIN_CLIENT_ID'),
                        'client_secret' => env('MOLTIN_CLIENT_SECRET'),
                    ],
                ]
            )
        );

        $this->cacheAccessToken(
            $accessToken = data_get($response, 'access_token'),
            data_get($response, 'expires_in')
        );

        return $accessToken;
    }

    /**
     * Removes any cached access token.
     *
     * @return void
     */
    protected function clearAccessToken()
    {
        return $this->redis->del($this::$cachedAccessTokenKey);
    }

    /**
     * Retreieves the cached access token.
     *
     * @return void
     */
    protected function getCachedAccessToken()
    {
        return $this->redis->get($this::$cachedAccessTokenKey);
    }

    /**
     * Sets the cached access token.
     *
     * @param string $accessToken
     * @param int $lifetime
     * @return void
     */
    protected function cacheAccessToken($accessToken, $lifetime)
    {
        $this->redis->set($this::$cachedAccessTokenKey, $accessToken, 'EX', $lifetime);
    }
}
