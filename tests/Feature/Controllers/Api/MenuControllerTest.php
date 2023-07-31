<?php

/**
 * @group moltin
 */
class MenuControllerTest extends TestCase
{
    public function test_it_can_get_top_sellers()
    {
        $response = $this->json('GET', '/api/top-sellers');

        $response->assertSuccessful();
    }
}
