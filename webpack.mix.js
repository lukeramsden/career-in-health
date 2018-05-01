let mix = require('laravel-mix');

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

mix.webpackConfig({
    resolve: {
        extensions: ['*', '.js', '.jsx', '.vue', '.json'],
    },
});

mix
    .js('resources/assets/js/app.js', 'public/js');

mix
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/pdf.scss', 'public/css');

mix
    .copy('node_modules/lity/dist/lity.min.css', 'public/css/lity.css');