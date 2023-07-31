<?php

use Purcell\Hone\Models\Page;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_show_aborts_with_404_if_no_category_is_found()
    {
        $response = $this->get(route('frontend.categories.show', str_random(64)));

        $response->assertStatus(404);
    }

    public function test_show_injects_correct_variable()
    {
        Page::insert(['title' => 'Home', 'uri' => 'home', 'template' => 'home']);

        // Requires category with slug 'test-category'
        // This can be resolved by using real fixture data once client provides this.

        $response = $this->get(route('frontend.categories.show', 'christmas-trees'));

        $response->assertViewHas('category', function ($category) {
            return $category['slug'] === 'christmas-trees';
        });
    }
}
