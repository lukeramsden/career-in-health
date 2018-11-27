<template>
  <div class="container mt-lg-5">
    <file-upload :dropzone-options="{
      url: '/',
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 20,
      maxFiles: 20,
      maxFilesize: 10,
      acceptedFiles: 'image/png,image/jpeg',
      autoQueue: false,
      addRemoveLinks: false,
    }" />
  </div>
</template>

<script>
import FileUpload from './FileUpload.vue';

//   if ( vm.options.destroyMediaOnRemove ) {
//   // remove file on server
//     axios
//       .post( route( 'media.destroy', { media: file.id } ) )
//       .then( ( response ) => { } )
//       .catch( ( error ) =>
//       {
//         console.log( error );
//         _.forIn(
//           error.response.data.errors,
//           ( errors,
//             field ) => errors.forEach( ( error )
// => toastr.error( error, changeCase.titleCase( field ) ) ),
//         );
//       } );
// }

// if ( vm.options.uploadTo )
// {
//   if ( !file.id )
//   {
//     const formData = new FormData();
//     formData.append( 'image', file );
//     self.emit( 'sending', file, undefined, formData );
//
//     axios.post( vm.uploadTo,
//       formData, {
//         // config
//         onUploadProgress: progressEvent =>
//         {
//           const percentCompleted = Math.floor(
//             ( progressEvent.loaded * 100 ) / progressEvent.total
//           );
//           self.emit( 'uploadprogress', file, percentCompleted, progressEvent.loaded );
//           console.log( `progress: ${file.name} is ${percentCompleted}%`
//             + `(${progressEvent.loaded}/${progressEvent.total}) complete` );
//         },
//       } )
//       .then( ( response ) =>
//       {
//         file.id = _.get( response, 'data.model.id' );
//         self.emit( 'success', file, response );
//         self.emit( 'complete', file );
//       } )
//       .catch( ( error ) =>
//       {
//         console.log( error );
//         self.emit( 'error', file, 'Error' );
//         _.forIn(
//           error.response.data.errors,
//           ( errors, field ) => errors.forEach(
//             ( e ) => toastr.error( e, changeCase.titleCase( field ) )
//           ),
//         );
//       } );
//   }
// }
// else
// {

// @if
//   ( $edit );
//   let files = [
//     @foreach( $address->getMedia( 'images' ) as $image )
//     {
//       id: {{ $image
// ->
//   id;
// }
// },
//   name: '{{ $image->name }}',
//     size;
// :
//   {{ $image->size; }}
// ,
//   dataURL: '{{ $image->getFullUrl() }}',
// },
// @endforeach
// ]
//   ;

// recursive IIFE used to ensure that files are added one-by-one
// ( function procFile()
// {
//   if ( files.length < 1 )
//     return;
//
//   let file = files.shift();
//
//   vm.dropzone.files.push( file );
//
//   // Call the default addedfile event handler
//   vm.dropzone.emit( 'addedfile', file );
//
//   vm.dropzone.createThumbnailFromUrl( file,
//     vm.dropzone.options.thumbnailWidth, vm.dropzone.options.thumbnailHeight,
//     vm.dropzone.options.thumbnailMethod, true, function ( thumbnail )
//     {
//       vm.dropzone.emit( 'thumbnail', file, thumbnail );
//       // Make sure that there is no progress bar, etc...
//       vm.dropzone.emit( 'complete', file );
//
//       vm.dropzone.options.maxFiles -= 1;
//       procFile( vm );
//     } );
// } )();
// @endif
// }

export default {
  components: {
    FileUpload,
  },
};
</script>
