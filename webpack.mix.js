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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/init.js', 'public/js')
    .vue()
    .js('resources/js/serviceworker.js', 'public')
    .js('resources/js/webpush.js', 'public/js')
    .copy('node_modules/alpinejs/dist/cdn.min.js', 'public/js/alpine.min.js')
    .copy('node_modules/clipboard/dist/clipboard.min.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/feather-icons/dist/feather-sprite.svg', 'public/svg')
    .copy('node_modules/bootstrap-icons/bootstrap-icons.svg', 'public/svg');

mix.browserSync('127.0.0.1:8000');
