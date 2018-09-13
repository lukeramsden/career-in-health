let mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        extensions: ['*', '.js', '.jsx', '.vue', '.json'],
    },
});

mix
    .js('resources/assets/js/app.js', 'public/js')
    .extract(['vue'])
    .sass('resources/assets/sass/app.scss', 'public/css', {
        includePaths: ['node_modules']
    })
    .sass('resources/assets/sass/pdf.scss', 'public/css')
    .copy('node_modules/lity/dist/lity.min.css', 'public/css/lity.css')
;

if (mix.inProduction()) {
    mix
        .version()
        .sourceMaps()
    ;
}