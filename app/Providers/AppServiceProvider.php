<?php

namespace App\Providers;

use App\Clients\Moltin;
use App\Clients\SagePay;
use Illuminate\Support\Str;
use App\ViewComponents\About;
use Purcell\Hone\Models\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use App\Repositories\PageRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\TopSellersRepository;
use App\Http\View\Composers\HeaderComposer;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMoltin();
        $this->registerSagePay();
        $this->registerMacros();
        $this->registerViewComposers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'includes.header',
            HeaderComposer::class
        );

        if (env('REDIRECT_HTTPS')) {
            URL::forceScheme('https');
        }
    }


    /**
     * Register's the Moltin services.
     *
     * @return void
     */
    protected function registerMoltin()
    {
        $this->app->singleton(Moltin::class, function ($app) {
            $client = new Moltin(
                app('cache'),
                env('MOLTIN_API_URL'),
                app(GuzzleClient::class),
                []
            );

            // This will automatically attach the cached Moltin access token where available.
            $client->setGuzzle([
                'headers' => [
                    'Authorization' => 'Bearer ' . $client->authenticate(),
                ],
            ]);

            return $client;
        });
    }

    protected function registerSagePay()
    {
        $this->app->singleton(SagePay::class, function ($app) {
            return new SagePay(
                env('SAGEPAY_URL'),
                app(GuzzleClient::class),
                [],
                config(ff() ? 'services.sagepay_ff.integrationKey' : 'services.sagepay.integrationKey'),
                config(ff() ? 'services.sagepay_ff.integrationPassword' : 'services.sagepay.integrationPassword'),
                config(ff() ? 'services.sagepay_ff.vendorName' : 'services.sagepay.vendorName')
            );
        });
    }

    /**
     * Registers additional Laravel macros.
     *
     * @return void
     */
    protected function registerMacros()
    {
        Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

            return (new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                $options
            ))->withPath(request()->url());
        });

        // Converts a Moltin collection into a suitable format for a menu.
        Collection::macro('convertToMenu', function () {
            return $this->transform(function ($category) {
                $products = collect(data_get($category, 'relationships.products.data', []))->transform(function ($product) {
                    return (object) [
                        'title' => data_get($product, 'name'),
                        'url' => ff()
                            ? route('frontend.ff.products.show', data_get($product, 'slug'))
                            : route('frontend.products.show', data_get($product, 'slug')),
                    ];
                });

                return (object) [
                    'products' => $products->take(5)->toArray(),
                    'title' => data_get($category, 'name'),
                    'url' => ff()
                        ? route('frontend.ff.categories.show', data_get($category, 'slug'))
                        : route('frontend.categories.show', data_get($category, 'slug')),
                ];
            });
        });
    }

    /**
     * Registers view composers.
     *
     * @return void
     */
    protected function registerViewComposers()
    {
        if (App::runningUnitTests()) {
            return;
        }

        view()->composer('layouts.base', function ($view) {
            $view->withFooterBody(page('Footer')->body);

            $view->withFooterCategoriesMenu(
                app(CategoryRepository::class)->getMenuFooterItems()
            );

            $view->withFooterInfoMenu(
                app(PageRepository::class)->getInfoPages()->transform(function ($page) {
                    $prefix = ff() ? '/festive-floristry/' : '/';
                    return (object) [
                        'url' => Str::start($page->uri, $prefix),
                        'title' => $page->title
                    ];
                })
            );
        });

        view()->composer('frontend.components.about', function ($view) {
            $page = ff() ? page('FF Home') : page('Home');

            $icon = $page->additional_field('about_icon');

            if (Storage::disk('public')->exists($icon)) {
                $filename = File::find($icon) ?: null;

                $icon = Storage::disk('public')->url($icon) . '/' . File::find($icon)->filename;
            }

            $about = new About([
                'icon' => $page->additional_field('about_icon'),
                'tagline' => $page->additional_field('about_tagline'),
                'left_column' => $page->additional_field('about_left_column'),
                'right_column' => $page->additional_field('about_right_column'),
            ]);

            $view->withComponent($about);
        });
    }
}
