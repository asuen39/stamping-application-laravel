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
mix.styles([
    'public/css/attendance.css',
    'public/css/bootstrap.min.css',
    'public/css/common.css',
    'public/css/mypage.css',
    'public/css/register.css',
    'public/css/reset.css',
    'public/css/stamp.css',
], 'public/css/all.css');
