<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Clients\Moltin;
use App\Clients\SagePay;
use App\Cart\SessionCartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;

class CheckoutController extends Controller
{
    protected $moltin;
    protected $sagePay;
    protected $sessionCartManager;

    public function __construct(Moltin $moltin, SagePay $sagePay, SessionCartManager $sessionCartManager)
    {
        $this->moltin = $moltin;
        $this->sagePay = $sagePay;
        $this->sessionCartManager = $sessionCartManager;
    }

    public function postFfCheckout()
    {
        $cartId = $this->checkCartInSession();

        try {
            // Checkout cart (with no payment, just enquiry)
            $response = $this->moltin->post(
                "v2/carts/{$cartId}/checkout",
                [
                    'json' => [
                        'data' => $data = $this->getOrderData(),
                    ]
                ]
            );

            // $orderId = data_get($response, 'id');

            return response()->json([
                'message' => 'Order processed successfully.',
                'redirect' => route('frontend.ff.thanks'),
            ]);
        } catch (ClientException $exception) {
            Log::error($exception);
            throw $exception;

            return response()->json([
                'toast' => [
                    'title' => 'Order Enquiry Failed!',
                    'msg' => 'Your order enquiry could not be made right now. Please try again later',
                    'type' => 'error',
                ]
            ], 400);
        }
    }

    public function postCheckoutComplete(Request $request)
    {
        $orderId = $request->route('orderId');

        $thanksRouterName = ff() ? 'frontend.ff.thanks' : 'frontend.thanks';

        $status = $this->sagePay->secureHandleResponse($request);

        // If successful, mark as paid in Moltin.
        if ($status) {
            $this->moltin->post(
                "v2/orders/{$orderId}/payments",
                [
                    'json' => [
                        'data' => [
                            'gateway' => 'manual',
                            'method' => 'authorize',
                        ]
                    ]
                ]
            );
        }

        return redirect()->route($thanksRouterName)->withStatus($status);
    }

    public function postCheckout()
    {
        $cartId = $this->checkCartInSession();

        request()->validate([
            'merchant_session_key' => 'required',
            'card_identifier' => 'required',
        ]);

        // Process payment
        try {

            // Checkout cart in Moltin
            $response = $this->moltin->post(
                "v2/carts/{$cartId}/checkout",
                [
                    'json' => ['data' => $data = $this->getOrderData()]
                ]
            );

            $orderId = data_get($response, 'id');

            // Attempt Sage transaction
            $transaction = $this->sagePay->makeTransaction(
                request()->input('merchant_session_key'),
                request()->input('card_identifier'),
                $this->sessionCartManager->calculateTotal(),
                $data
            );

            Log::info('NEW TRANSACTION ATTEMPT:');
            Log::info(json_encode($transaction));


            // Requires 3D secure
            if (data_get($transaction, 'statusCode') == '2007') {
                return response()->json([
                    'title' => '3D Secure',
                    '3d_secure' => true,
                    'transaction' => json_encode($transaction),
                    'finish_url' => route('checkout-complete', [$orderId]),
                ], 200);
            }


            // If anything other than a success or redirect, we'll fail.
            if (data_get($transaction, 'statusCode') != '0000') {
                return response()->json([
                    'title' => 'Payment Failed!',
                    'msg' => data_get($transaction, 'statusDetail', 'Your payment could not be processed right now.'),
                    'type' => 'error',
                ], 400);
            }

            // Register a manual payment against the order on Moltin
            $response = $this->moltin->post(
                "v2/orders/{$orderId}/payments",
                [
                    'json' => [
                        'data' => [
                            'gateway' => 'manual',
                            'method' => 'authorize',
                        ]
                    ]
                ]
            );

            return response()->json([
                'message' => 'Order processed successfully.',
                'redirect' => route(request()->input('isFF') ? 'frontend.ff.thanks' : 'frontend.thanks'),
            ]);
        } catch (ClientException $exception) {
            // Removes custom items from the Cart if an order is failed.
            // $cart = $this->sessionCartManager->getCart();
            // foreach($cart as $cartItem) {
            //     if($cartItem["type"] == "custom_item") {
            //         $this->sessionCartManager->destroyCartItem($cartItem["id"]);
            //     }
            // }

            Log::error($exception);

            return response()->json([
                'toast' => [
                    'title' => 'Payment Failed!',
                    'msg' => 'Your payment could not be made right now. Please try again later',
                    'type' => 'error',
                ]
            ], 400);
        }
    }

    protected function checkCartInSession()
    {
        $cartId = $this->sessionCartManager->getCartIdFromSession();

        if (is_null($cartId)) {
            return response()->json([
                'message' => 'No cart in session',
            ], 400);
        }

        return $cartId;
    }

    protected function getOrderData()
    {
        return [
            'customer' => request()->input('customer'),
            'billing_address' => $this->clearBlanks(request()->input('billing_address')),
            'shipping_address' => $this->clearBlanks(request()->input('shipping_address')),
        ];
    }

    protected function clearBlanks($array)
    {
        // Moltin doesn't like blank strings.
        return collect($array)->reject(function($data, $key) {
            return empty($data);
        })->toArray();
    }
}
