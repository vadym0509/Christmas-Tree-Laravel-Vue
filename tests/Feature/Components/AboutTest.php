<?php

use App\ViewComponents\About;
use Purcell\Hone\Models\Page;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AboutTest extends TestCase
{
    use DatabaseMigrations;

    public function test_view_states()
    {
        // Test it doesn't break rendering an empty state.
        $about = (new About([
            'tagline' => null,
            'left_column' => null,
            'right_column' => null,
        ]))->render();
        $about = (new About([]))->render();

        // Test data is placed correctly in a full state
        $about = new About([
            'tagline' => $tagline = 'my tagline',
            'left_column' => $leftColumn = 'some left content',
            'right_column' => $rightColumn = 'some right content',
        ]);
        $dom = new Crawler($output = $about->render());

        $this->assertContains($tagline, $dom->filter( '.about_headline')->text());
        $this->assertContains($leftColumn, $dom->filter( '.about_text_left')->text());
        $this->assertContains($rightColumn, $dom->filter( '.about_text_right')->text());
    }

    // TODO: Add ff-state test
}
