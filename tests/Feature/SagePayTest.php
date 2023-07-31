<?php

use App\Clients\SagePay;

class SagePayTest extends TestCase
{
    public function test_it_can_get_merchant_session_keys()
    {
        $response = app(SagePay::class)->getMerchantSessionKey();

        $this->assertTrue(is_string($response));
    }
}
