<template>
  <div></div>
</template>

<script>
import * as Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';

export default {
  props: {
    dropzoneOptions: Object,
    options: {
      type: Object,
      default()
      {
        return {
          uploadTo: null,
        };
      },
    },
  },
  data()
  {
    return {
      dropzone: null,
    };
  },
  mounted()
  {
    // const dropzoneOptions = {
    //   // options
    //   url: '/',
    //   autoProcessQueue: false,
    //   uploadMultiple: true,
    //   parallelUploads: 20,
    //   maxFiles: 20,
    //   maxFilesize: 10,
    //   acceptedFiles: 'image/png,image/jpeg',
    //   autoQueue: false,
    //   addRemoveLinks: false,
    // };

    const vm = this;

    this.dropzone = new Dropzone( this.$el, this.dropzoneOptions );

    this.dropzone.on( 'addedfile', function ( file )
    {
      if ( this.files.length > 20 )
      {
        toastr.error( 'Too many images' );
        // Remove the file preview.
        this.removeFile( file );
        return;
      }

      // Create the remove button
      const removeButton = Dropzone.createElement(
        '<button class="btn btn-outline-danger btn-sm btn-block">Remove file</button>'
      );

      // Capture the Dropzone instance as closure.
      const self = this;

      // Listen to the click event
      removeButton.addEventListener( 'click', ( e ) =>
      {
        // Make sure the button click doesn't submit the form:
        e.preventDefault();
        e.stopPropagation();

        // Remove the file preview.
        self.removeFile( file );

        vm.$emit( 'removed', file );

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
      } );

      // Add the button to the file preview element.
      file.previewElement.appendChild( removeButton );

      if ( vm.options.uploadTo )
      {
        if ( !file.id )
        {
          const formData = new FormData();
          formData.append( 'image', file );
          self.emit( 'sending', file, undefined, formData );

          axios.post( vm.uploadTo,
            formData, {
              // config
              onUploadProgress: progressEvent =>
              {
                const percentCompleted = Math.floor(
                  ( progressEvent.loaded * 100 ) / progressEvent.total
                );
                self.emit( 'uploadprogress', file, percentCompleted, progressEvent.loaded );
                console.log( `progress: ${file.name} is ${percentCompleted}%`
                  + `(${progressEvent.loaded}/${progressEvent.total}) complete` );
              },
            } )
            .then( ( response ) =>
            {
              file.id = _.get( response, 'data.model.id' );
              self.emit( 'success', file, response );
              self.emit( 'complete', file );
            } )
            .catch( ( error ) =>
            {
              console.log( error );
              self.emit( 'error', file, 'Error' );
              _.forIn(
                error.response.data.errors,
                ( errors, field ) => errors.forEach(
                  ( e ) => toastr.error( e, changeCase.titleCase( field ) )
                ),
              );
            } );
        }
      }
      else
      {
        self.emit( 'complete', file );
      }
    } );

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

    vm.dropzone.enable();

    const emitUpdate = ( /* file */ ) => vm.$emit( 'update', vm.dropzone.getAcceptedFiles() );
    vm.dropzone.on( 'addedfiles', emitUpdate );
    vm.dropzone.on( 'removedfile', emitUpdate );
  },
  beforeDestroy()
  {
    this.dropzone.disable();
    this.dropzone = null;
  },
};
</script>
