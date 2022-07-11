const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public');
mix.setResourceRoot('../');

mix.combine([
    'resources/css/app.css',
], 'public/css/app.css', 'public/css');

mix.combine([
    'resources/js/app.js',
], 'public/js/app.js', 'public/js');

mix.combine([
    'resources/plugins/jquery.js'
], 'public/js/jquery.js', 'public/js');

mix.combine([
    'resources/plugins/bootstrap.js'
], 'public/js/bootstrap.js', 'public/js');

mix.combine([
    'resources/css/bootstrap.css'
], 'public/css/bootstrap.css', 'public/css');

