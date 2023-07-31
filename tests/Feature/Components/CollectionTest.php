<?php

use Mockery as m;
use App\ViewComponents\Collection;
use Symfony\Component\DomCrawler\Crawler;
use App\Repositories\CollectionRepository;
use App\Exceptions\ViewComponentRenderException;

class CollectionTest extends TestCase
{
    public function test_empty_state()
    {
        // Test no collection set errors correctly
        $this->expectException(ViewComponentRenderException::class);
        $component = (new Collection([]))->render();

        // Test that it handles failures to load a collection (e.g. random collection)
        $this->expectException(ViewComponentRenderException::class);
        $component = (new Collection(['moltin_collection_key' => str_random(32)]))->render();

        $this->assertTrue(true);
    }

    public function test_it_prints_empty_collections_properly()
    {
        $collectionRepository = m::mock(CollectionRepository::class);
        $collectionRepository->shouldReceive('getBySlug')->with('foo')->andReturn(
            $this->collectionWithNoProducts()
        );

        $this->app->instance(CollectionRepository::class, $collectionRepository);

        $output = (new Collection(['moltin_collection_key' => 'foo']))->render();
        $dom = new Crawler($output);
        $this->assertContains('My Other Collection', $dom->filter('p.collection_title')->text());
    }

    public function test_it_prints_full_collections_properly()
    {
        $collectionRepository = m::mock(CollectionRepository::class);
        $collectionRepository->shouldReceive('getBySlug')->with('foo')->andReturn(
            $collection = $this->collectionWithProducts()
        );

        $this->app->instance(CollectionRepository::class, $collectionRepository);

        $output = (new Collection(['moltin_collection_key' => 'foo']))->render();
        $dom = new Crawler($output);
        $this->assertEquals(2, $dom->filter('a.collection_item')->count());
        $this->assertContains('My Other Collection', $dom->filter( '.collection_item_body_category')->text());
        $dom->filter( '.collection_item_body_name')->each(function($node, $key) use($collection) {
            return $node->html() == $collection['relationships']['products'][$key]['name'];
        });
        $dom->filter( '.collection_item_body_params')->each(function ($node, $key) use ($collection) {
            // TODO .. height here ???
        });
    }

    protected function collectionWithNoProducts()
    {
        return [
            'name' => 'My Other Collection',
            'slug' => 'my-other-collection',
            'description' => '',
            'relationships' => [
                'products' => [],
            ],
        ];
    }

    protected function collectionWithProducts()
    {
        // Second product has no image.

        return [
            'name' => 'My Other Collection',
            'slug' => 'my-other-collection',
            'description' => '',
            'relationships' => [
                'products' => [
                    [
                        'name' => 'My Product',
                        'slug' => 'my-product',
                        'relationships' => [
                            'main_image' => [
                                'link' => [
                                    'href' => 'http://google.co.uk',
                                ]
                            ],
                        ]
                    ], [
                        'name' => 'My Product 2',
                        'slug' => 'my-product-2',
                        'relationships' => [
                            'main_image' => null,
                        ]
                    ]
                ],
            ],
        ];
    }
}
