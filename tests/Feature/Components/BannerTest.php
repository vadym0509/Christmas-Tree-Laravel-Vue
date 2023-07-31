<?php

use App\ViewComponents\Banner;
use Symfony\Component\DomCrawler\Crawler;

class BannerTest extends TestCase
{
    public function test_cta_button_is_hidden_when_link_or_text_are_null()
    {
        $banner = new Banner(['cta_link' => null, 'cta_text' => null]);
        $this->assertFalse($banner->shouldDisplayCta());

        $banner = new Banner(['cta_link' => '#', 'cta_text' => null]);
        $this->assertFalse($banner->shouldDisplayCta());

        $banner = new Banner(['cta_link' => null, 'cta_text' => 'somewhere']);
        $this->assertFalse($banner->shouldDisplayCta());

        $banner = new Banner(['cta_link' => '#', 'cta_text' => 'somewhere']);
        $this->assertTrue($banner->shouldDisplayCta());
    }

    /**
     * @group e2e
     */
    public function test_view_states()
    {
        // Test it doesn't break rendering an empty state.
        $banner = (new Banner([
            'image' => null,
            'icon' => null,
            'header' => null,
            'cta_link' => null,
            'cta_text' => null,
            'body' => null,
        ]))->render();
        $banner = (new Banner([]))->render();

        // Test data is placed correctly in a full state
        $banner = new Banner([
            'image' => $image = 'http://google.co.uk',
            'icon' => $icon = 'asset.png',
            'header' => $header = 'My header',
            'cta_link' => $ctaLink = '#somewhere',
            'cta_text' => $ctaText = 'Hello!',
            'body' => $body = 'Foo bar',
        ]);
        $dom = new Crawler($output = $banner->render());

        $this->assertContains(asset($icon), head($dom->filter( '.banner_content_icon > img[alt=icon]')->extract('src')));
        $this->assertContains($header, $dom->filter( '.banner_content_icon > p')->text());
        $this->assertEquals($image, head($dom->filter('img.banner_img')->extract('src')));
        $this->assertEquals($ctaLink, head($dom->filter( 'a.banner_content_body_button')->extract('href')));
        $this->assertContains($ctaText, $dom->filter( 'a.banner_content_body_button')->text());
    }
}
