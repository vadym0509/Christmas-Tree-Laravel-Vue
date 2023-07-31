<?php

use App\Clients\Moltin;

class ClientTest extends TestCase
{
    /**
     * @group e2e
     */
    public function test_moltin_is_constructable()
    {
        $this->assertTrue(
            app(Moltin::class)->heartbeat()
        );
    }
}
