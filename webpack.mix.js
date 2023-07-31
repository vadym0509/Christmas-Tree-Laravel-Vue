const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/assets/frontend/js')
    .sass('resources/sass/app.scss', 'public/assets/frontend/css')
    .copyDirectory('resources/images', 'public/assets/frontend/images')
    .copyDirectory('vendor/purcell/hone/src/public/assets/backend', 'public/assets/backend');
