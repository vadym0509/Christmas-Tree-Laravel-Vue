<?php

namespace App\Clients;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MatthewErskine\Guzzle\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class SagePay extends Client
{
    const SECURE_STATUS_AUTHENTICATED = 'Authenticated';
    const TRANSACTION_STATUS_OK = 'Ok';

    protected $vendorName;
    protected $integrationKey;
    protected $integrationPassword;

    public function __construct($url, GuzzleClient $httpClient, $guzzle = [], $integrationKey, $integrationPassword, $vendorName)
    {
        parent::__construct($url, $httpClient, $guzzle);

        $this->vendorName = $vendorName;
        $this->integrationKey = $integrationKey;
        $this->integrationPassword = $integrationPassword;
    }

    public function getMerchantSessionKey()
    {
        return data_get($this->respond(
            $this->getHttpClient()->post(
                $this->getUrl().'/merchant-session-keys', [
                    'auth' => [$this->integrationKey, $this->integrationPassword],
                    'json' => [
                        'vendorName' => $this->vendorName,
                    ]
                ]
            )
        ), 'merchantSessionKey');
    }

    public function secureHandleResponse(Request $request): bool
    {
        $transactionId = $request->MD;

        try {
            $response = $this->httpClient->post("{$this->url}/transactions/{$transactionId}/3d-secure", [
                'auth' => [$this->integrationKey, $this->integrationPassword],
                'json' => [
                    "paRes" => $request->get('PaRes'),
                ],
            ]);
        } catch (ClientException $exception) {
            // Customer tried to POST same Sagepay auth again (pressed back button), we need to cancel.
            Log::warning('(Customer tried to refresh on Sagepay)');
            throw $exception;
        }

        $responseBody = $this->respond($response);
        $response3DSecureStatus = $responseBody['status'] ?? null;

        Log::info('NEW SAGEPAY RESPONSE (CODE: '.$response->getStatusCode().')');
        Log::info(json_encode($responseBody));

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            Log::warning('Sagepay transaction failed (Not 201)');

            return false;
        }

        $transactionResponseBody = $this->make3DSecureTransaction($transactionId);
        $transactionResponseStatus = $transactionResponseBody['status'] ?? null;

        Log::info('NEW SAGEPAY TRANSACTION RESPONSE:');
        Log::info(json_encode($transactionResponseBody));

        if ($transactionResponseStatus !== self::TRANSACTION_STATUS_OK) {
            Log::warning('Sagepay transaction failed (Transaction status is not OK)');

            return false;
        }

        Log::info('Sagepay transaction successful');

        return true;
    }

    public function makeTransaction($merchantSessionKey, $cardIdentifier, $amount, $customerData)
    {
        return $this->respond(
            $this->getHttpClient()->post(
                $this->getUrl() . '/transactions',
                [
                    'auth' => [$this->integrationKey, $this->integrationPassword],
                    'json' => [
                        'transactionType' => 'Payment',
                        'paymentMethod' => [
                            'card' => [
                                'merchantSessionKey' => $merchantSessionKey,
                                'cardIdentifier' => $cardIdentifier,
                            ]
                        ],
                        'vendorName' => $this->vendorName,
                        'vendorTxCode' => Uuid::uuid1(),
                        'amount' => $amount,
                        'currency' => 'GBP', // TODO: Discovery multi currency support
                        'description' => 'Transaction',
                        'apply3DSecure' => 'UseMSPSetting',
                        'customerFirstName' => data_get($customerData, 'billing_address.first_name'),
                        'customerLastName' => data_get($customerData, 'billing_address.last_name'),
                        'billingAddress' => [
                            'address1' => data_get($customerData, 'shipping_address.line_1'),
                            'city' => data_get($customerData, 'shipping_address.city'),
                            'country' => 'GB',
                            'postalCode' => data_get($customerData, 'shipping_address.postcode'),
                            'entryMethod' => 'Ecommerce',
                        ],
                    ]
                ]
            )
        );
    }

    private function make3DSecureTransaction(string $transactionId): array
    {
        //TODO: Need to refactor!
        $token = base64_encode("$this->integrationKey:$this->integrationPassword");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->url}/transactions/{$transactionId}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic {$token}",
                "Cache-Control: no-cache"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
