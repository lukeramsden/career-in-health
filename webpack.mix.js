// eslint-disable-next-line import/no-extraneous-dependencies
const webpack = require( 'webpack' );
const mix     = require( 'laravel-mix' );

mix.webpackConfig( {
  resolve: {
    extensions: [ '*', '.js', '.jsx', '.vue', '.json' ],
    alias: {
      // eslint-disable-next-line no-undef
      '@': path.resolve( 'resources/assets/sass' ),
    },
  },
  plugins: [
    new webpack.ContextReplacementPlugin( /moment[/\\]locale$/, /en/ ),
  ],
} );

mix
  .js( 'resources/assets/js/app.js', 'public/js' )
  .extract( [ 'vue', 'lodash', 'axios', 'moment', 'jquery' ] )
  .sass( 'resources/assets/sass/app.scss', 'public/css', {
    includePaths: [ 'node_modules' ],
  } )
  .sass( 'resources/assets/sass/pdf.scss', 'public/css' )
  .copy( 'node_modules/lity/dist/lity.min.css', 'public/css/lity.css' )
;

if ( mix.inProduction() )
{
  mix
    .version()
  ;
}
else
{
  mix
    .sourceMaps()
    .webpackConfig( {
      module: {
        rules: [
          {
            enforce: 'pre',
            test: /\.(js|vue)$/,
            exclude: /node_modules/,
            loader: 'eslint-loader',
          },
        ],
      },
    } )
  ;
}
