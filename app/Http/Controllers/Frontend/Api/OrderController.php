<?php


namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderAdminCreated;
use App\Mail\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Clients\Moltin;


class OrderController extends Controller
{
    /**
     * Order data
     *
     * @var object
     */
    protected $orderData;

    /**
     * @var Moltin
     */
    protected $moltin;

    /**
     * OrderController constructor.
     * @param Moltin $moltin
     */
    public function __construct(Moltin $moltin)
    {
        return $this->moltin = $moltin;
    }

    /**
     * Get and handle data from Moltin WebHook
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function mailDataHandler(Request $request)
    {
        if ($request->configuration['secret_key'] !== env('ORDER_MAIL_SECRET_KEY')) {
            return false;
        }

        $order = $request->payload;
        $products = collect($order['included']['items']);
        $deliveryIndex = $products->pluck('name')->search('Delivery');
        $delivery = data_get($products, $deliveryIndex);
        $filteredProducts = $products->forget($deliveryIndex);

        // get full Product and options for each Order item
        $filteredProducts->transform(function ($orderItem){
            $productItem = $this->moltin->getProductById($orderItem['product_id']);
            $parentProduct = null;
            $variation = null;

            if(isset($productItem['relationships']['parent'])) {
                $parentProduct = $this->moltin->getProductById($productItem['relationships']['parent']['data']['id']);

                $variationId = array_search($orderItem['product_id'], $parentProduct['meta']['variation_matrix']);

                foreach ($parentProduct['meta']['variations'] as $value){
                    $collection = collect($value['options']);
                    $variation = $collection->first(function ($variationItem) use($variationId) {
                        return $variationItem['id'] === $variationId;
                    });
                    if ($variation) {
                        break;
                    }
                }
            }
            $fullProduct = $parentProduct ?: $productItem;

            data_set($orderItem, 'selectedOption', $variation, true);
            return data_set($orderItem, 'fullProduct', $fullProduct, true);
        });

        data_fill($order, 'data.delivery', $delivery);
        data_set($order, 'included.items', $filteredProducts);

        $this->orderData = $order;

        return $this->sendOrderMails();
    }

    /**
     * Send e-mails using Order info
     *
     * @return mixed
     */
    protected function sendOrderMails() {
        Mail::to(env('ORDER_MAIL_ADMIN'))
            ->send(new OrderAdminCreated($this->orderData));

        return Mail::to($this->orderData['data']['customer']['email'])
            ->send(new OrderCreated($this->orderData));
    }
}
