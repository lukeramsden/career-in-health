<template>
  <div class="dropzone"></div>
</template>

<script>
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';

Dropzone.autoDiscover = false;

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

    vm.dropzone = new Dropzone( vm.$el, this.dropzoneOptions );

    vm.dropzone.on( 'addedfile', function ( file )
    {
      const self = this;

      if ( self.files.length > 20 )
      {
        toastr.error( 'Too many images' );
        // Remove the file preview.
        self.removeFile( file );
        return;
      }

      // Create the remove button
      const removeButton = Dropzone.createElement( `
<button class="btn btn-outline-danger btn-sm btn-block">Remove file</button>
` );

      // Capture the Dropzone instance as closure.

      // Listen to the click event
      removeButton.addEventListener( 'click', ( e ) =>
      {
        // Make sure the button click doesn't submit the form:
        e.preventDefault();
        e.stopPropagation();

        // Remove the file preview.
        self.removeFile( file );

        vm.$emit( 'removed', file );
      } );

      // Add the button to the file preview element.
      file.previewElement.appendChild( removeButton );

      self.emit( 'complete', file );
      vm.$emit( 'added', file );
    } );

    vm.dropzone.enable();

    const emitUpdate = ( /* file */ ) => vm.$emit( 'update', vm.dropzone.getAcceptedFiles() );
    vm.dropzone.on( 'addedfiles', emitUpdate );
    vm.dropzone.on( 'removedfile', emitUpdate );
  },
  beforeDestroy()
  {
    this.dropzone.off();
    this.dropzone.disable();
    this.dropzone = null;
  },
};
</script>
