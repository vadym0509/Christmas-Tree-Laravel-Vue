<?php
use App\Models\Promotion;
use Purcell\Hone\Models\Page;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_requires_home_page_to_run()
    {
        // The admin must create a page called "Home" for this route to work.

        $response = $this->get(route('frontend.home'));
        $response->assertStatus(404);

        Page::insert(['title' => 'Home', 'uri' => 'home']);
        $response = $this->get(route('frontend.home'));
        $response->assertStatus(200);
    }

    public function test_components_are_receiving_correct_data()
    {
        Page::insert(['title' => 'Home', 'uri' => 'home', 'template' => 'home']);
        $page = Page::first();

        // We'll select a field for each component.
        $page->additional_fields = [
            'banner_image' => $bannerImage = 'my_banner_image',
            'about_tagline' => $aboutTagline = 'Here is my tagline',
            'promotional_collection' => $collectionKey = 'some-collection',
        ];
        $page->save();

        $response = $this->get(route('frontend.home'));

        // Test components have loaded with additional data correctly.
        $response->assertViewHas('banner', function($component) use($bannerImage) {
            return $component->image === $bannerImage;
        });
        $response->assertViewHas('about', function ($component) use ($aboutTagline) {
            return $component->tagline === $aboutTagline;
        });
        $response->assertViewHas('collection', function ($component) use ($collectionKey) {
            return $component->moltin_collection_key === $collectionKey;
        });
    }

    public function test_promotions_are_loaded_on_page()
    {
        Page::insert(['title' => 'Home', 'uri' => 'home', 'template' => 'home']);
        Promotion::insert(['title' => 'foo', 'body' => 'bar']);
        Promotion::insert(['title' => 'ying', 'body' => 'yang']);

        $response = $this->get(route('frontend.home'));

        $response->assertViewHas('promo', function ($component) {
            return count($component->promotions) === 2;
        });
    }
}
