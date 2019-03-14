// https://stackoverflow.com/a/49388446
module.exports = ( obj, rootName, ignoreList, debug = false ) =>
{
  const formData = new FormData();

  function ignore( root )
  {
    return Array.isArray( ignoreList )
      && ignoreList.some( ( x ) => x === root );
  }

  function appendFormData( data, root )
  {
    if ( debug )
      console.log( root, data );

    if ( !ignore( root ) )
    {
      root = root || '';
      if ( data instanceof File )
      {
        formData.append( root, data );
      }
      else if ( Array.isArray( data ) )
      {
        for ( let i = 0; i < data.length; i++ )
        {
          appendFormData( data[ i ], `${root}[${i}]` );
        }
      }
      else if ( typeof data === 'object' && data )
      {
        Object.keys( data ).forEach( key =>
        {
          if ( Object.prototype.hasOwnProperty.call( data, key ) )
          {
            if ( root === '' )
            {
              appendFormData( data[ key ], key );
            }
            else
            {
              appendFormData( data[ key ], `${root}.${key}` );
            }
          }
        } );
      }
      else if ( data !== null && typeof data !== 'undefined' )
      {
        formData.append( root, data );
      }
    }
    else if ( debug )
      console.log( `IGNORE ${root}` );
  }

  appendFormData( obj, rootName );

  if ( debug )
    console.log( formData.get(rootName) );

  return formData;
};
