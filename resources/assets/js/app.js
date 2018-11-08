window.Popper = require( 'popper.js' ).default;
window.$      = window.jQuery = require( 'jquery' );

require( 'bootstrap' );

window._          = require( 'lodash' );
window.lity       = require( 'lity' );
window.mime       = require( 'mime' );
window.filesize   = require( 'file-size' );
window.changeCase = require( 'change-case' );
window.fuzzaldrin = require( 'fuzzaldrin-plus' );
window.io         = require( 'socket.io-client' );
window.axios      = require( 'axios' );
window.moment     = require( 'moment' );

window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';

const token = document.head.querySelector( 'meta[name="csrf-token"]' );

if ( token )
{
  window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = token.content;
}
else
{
  console.error( 'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token' );
}

require( './initVue' );

/*
 * Replace all SVG images with inline SVG
 */
jQuery( 'img.svg-inline' ).each( function ()
{
  const $img     = jQuery( this );
  const imgID    = $img.attr( 'id' );
  const imgClass = $img.attr( 'class' );
  const imgURL   = $img.attr( 'src' );

  jQuery.get( imgURL, ( data ) =>
  {
    // Get the SVG tag, ignore the rest
    let $svg = jQuery( data ).find( 'svg' );

    // Add replaced image's ID to the new SVG
    if ( typeof imgID !== 'undefined' )
    {
      $svg = $svg.attr( 'id', imgID );
    }

    // Add replaced image's classes to the new SVG
    if ( typeof imgClass !== 'undefined' )
    {
      $svg = $svg.attr( 'class', `${imgClass} replaced-svg` );
    }

    // Remove any invalid XML tags as per http://validator.w3.org
    $svg = $svg.removeAttr( 'xmlns:a' );

    // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
    if ( !$svg.attr( 'viewBox' ) && $svg.attr( 'height' ) && $svg.attr( 'width' ) )
    {
      $svg.attr( 'viewBox', `0 0 ${$svg.attr( 'height' )} ${$svg.attr( 'width' )}` );
    }

    // Replace image with new SVG
    $img.replaceWith( $svg );
  }, 'xml' );
} );
