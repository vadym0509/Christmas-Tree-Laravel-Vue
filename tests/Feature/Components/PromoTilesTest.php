<?php

use App\ViewComponents\PromoTiles;
use Symfony\Component\DomCrawler\Crawler;

class PromoTilesTest extends TestCase
{
    public function test_view_states()
    {
        // This component has ff toggle state, so let's mimick a request.
        $this->get('/');

        // Test it doesn't break rendering an empty state.
        (new PromoTiles([
            'promotions' => [],
        ]))->render();
        (new PromoTiles([]))->render();

        // Test data is placed correctly in a full state
        $promoTiles = new PromoTiles([
            'promotions' => [
                (object) [
                    'imagePath' => $imagePath = 'http://placehold.it/200x200',
                    'title' => $title = 'Test',
                    'body' => $body = 'Here is my example',
                    'cta_link' => $ctaLink = 'http://google.co.uk',
                    'cta_text' => $ctaText = 'Google',
                ],
            ],
        ]);

        $dom = new Crawler($output = $promoTiles->render());

        // TODO: Repair this test
        $this->markTestIncomplete();

        $this->assertContains($imagePath, $dom->filter( '.promo_item_img img')->extract('src'));
        $this->assertContains($title, $dom->filter( '.promo_item_body .title')->text());
        $this->assertContains($body, $dom->filter( '.promo_item_body p:not(.title)')->text());
        $this->assertContains($ctaText, $dom->filter( '.promo_item_body a')->text());
        $this->assertContains($ctaLink, $dom->filter( '.promo_item_body a')->extract('href'));
    }

    public function test_cta_link_is_only_rendered_if_present()
    {
        $promoTiles = new PromoTiles([
            'promotions' => [
                (object) [
                    'imagePath' =>'http://placehold.it/200x200',
                    'title' => 'Test',
                    'body' => 'Here is my example',
                    'cta_link' => $ctaLink = null,
                    'cta_text' => $ctaText = null,
                ],
            ],
        ]);
        $dom = new Crawler($output = $promoTiles->render());

        $this->markTestIncomplete();

        // TODO: Check dom
    }
}
