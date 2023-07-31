<?php

namespace App\Http\Controllers\Frontend;

use Purcell\Hone\Models\File;
use App\ViewComponents\Banner;
use App\ViewComponents\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CategoryRepository;
use App\ViewComponents\YouMayAlsoLike;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Shows a category.
     *
     * @param string $categorySlug
     * @return \Illuminate\Http\Response
     */
    public function show($categorySlug)
    {
        abort_unless(
            $category = app(CategoryRepository::class)->getBySlug($categorySlug),
            404, 'Category not found'
        );

        abort_if(
            ff() && data_get($category, 'festive-floristry', false) == false,
            404, 'Category is FF only'
        );

        if (Storage::disk('public')->exists($category['banner_image'])) {
            $filename = File::find($category['banner_image']) ?: null;

            $category['banner_image'] = Storage::disk('public')->url($category['banner_image']) . '/' . File::find($category['banner_image'])->filename;
        }

        $banner = new Banner([
            'image' => data_get($category, 'banner_image'),
            'icon' => null, // Tree icon should go here.
            'header' => data_get($category, 'name'),
            'cta_link' => null,
            'cta_text' => null,
            'body' => null,
        ]);

        $youMayAlsoLike = new YouMayAlsoLike([
            'context_data' => $category,
            'context_type' => 'category',
        ]);

        return view('frontend.categories.show', compact('banner', 'category', 'youMayAlsoLike'));
    }
}
