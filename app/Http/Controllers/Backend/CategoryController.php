<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use stdClass;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * The category repository instance.
     *
     * @var \App\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Called when the class should load.
     *
     * @param \App\Repositories\CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getPaginated();

        return view(
            'backend.categories.index',
            [
                'collection' => $categories,
                'view_data' => $this->viewData(),
            ]
        );
    }

    public function edit($id)
    {
        $model = $this->categoryRepository->getById($id);

        data_set($model, 'description', data_get($model, 'description', null));
        data_set($model, 'banner_image', data_get($model, 'banner_image', null));

        return view('backend.categories.edit', [
            'model' => (object) $model,
            'view_data' => $this->viewData(),
        ]);
    }

    public function update($id)
    {
        request()->validate(['image' => 'integer', 'description' => 'string']);

        $data = array_merge($this->categoryRepository->getById($id), request()->input());

        $this->categoryRepository->update($id, array_only($data, [
            'id',
            'name',
            'type',
            'slug',
            'description',
            'status'
        ]) + ['banner_image' => $data['image']]);

        flash(trans('hone::backend_flash_messages.update_success'), 'success');
        return redirect()->back();
    }

    /**
     * Return an array of data that can be share with views
     *
     * @return array
     */
    private function viewData()
    {
        return [
            'plural_name' => 'Categories',
            'routes_prefix' => 'backend.categories',
            'form_view' => 'backend.categories.form',
            'tbody_view' => 'backend.categories._partials.tbody',
            'title_menu_view' => 'backend.categories._partials.title_menu',
            'views_directory' => 'backend.categories',
        ];
    }
}
