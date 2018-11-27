<template>
  <div class="container-fluid mt-lg-3">
    <template v-if="loaded">
      <div class="card-columns smaller-card-columns">
        <form novalidate @submit.stop.prevent="">
          <div class="card card-custom">
            <div class="card-body">
              <div class="form-group">
                <label>Name (<span class="text-action">*</span>)</label>
                <input v-model="address.name"
                       type="text"
                       class="form-control"
                       name="name"
                       maxlength="120"
                       required>
              </div>

              <div class="form-group">
                <label>Address (<span class="text-action">*</span>)</label>
                <input v-model="address.address_line_1"
                       type="text"
                       class="form-control my-1"
                       name="address_line_1"
                       placeholder="Line 1"
                       maxlength="60"
                       required>
                <input v-model="address.address_line_2"
                       type="text"
                       class="form-control my-1"
                       name="address_line_2"
                       placeholder="Line 2"
                       maxlength="60">
                <input v-model="address.address_line_3"
                       type="text"
                       class="form-control my-1"
                       name="address_line_3"
                       placeholder="Line 3"
                       maxlength="60">
              </div>

              <div class="form-group">
                <label>Postcode (<span class="text-action">*</span>)</label>
                <input v-model="address.postcode"
                       type="text"
                       class="form-control my-1"
                       name="postcode"
                       placeholder="Post Code"
                       maxlength="60">
              </div>

              <div class="form-group">
                <label>About</label>
                <textarea v-model="address.about" name="about"
                          class="form-control" placeholder="500 characters"
                          rows="8"
                          maxlength="500"></textarea>
              </div>

              <div class="form-group">
                <label>Contact Details</label>
                <input
                  v-model="address.phone"
                  type="tel"
                  class="form-control my-1"
                  placeholder="Phone Number"
                  name="phone">

                <input
                  v-model="address.email"
                  type="email"
                  class="form-control my-1"
                  placeholder="Email Address"
                  name="email">
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <div class="form-group">
                <label>City (<span class="text-action">*</span>)</label>
                <select2 v-model="address.location_id"
                         required
                         name="location_id">
                  <option :value="null">-</option>
                  <option v-for="location in locations"
                          :key="location.id"
                          :value="location.id">{{ location.name }}
                  </option>
                </select2>
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <label>Image Gallery</label>
              <dropzone id="dropzone" ref="file-dz" :options="dzOpts" />
            </div>
          </div>

          <div class="card card-custom-material card-custom-material-hover card-custom-no-top-bar">
            <div class="btn-group btn-group-full btn-group-vertical">
              <button type="submit"
                      class="btn btn-action"
                      @click="submit">
                {{ editing ? 'Save' : 'Create' }}
              </button>

              <template v-if="editing">
                <a class="btn btn-danger btn-block"
                   @click="destroy">Delete</a>
                <a :href="route('address.show', {address: addressId})"
                   class="btn btn-info btn-block">View Address</a>
              </template>
            </div>
          </div>
        </form>
      </div>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

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
    Dropzone,
  },
  props: {
    addressId: {
      type: Number,
      default() { return null; },
    },
  },
  data()
  {
    return {
      dzOpts: {
        url: '/',
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 20,
        maxFiles: 20,
        maxFilesize: 10,
        acceptedFiles: 'image/png,image/jpeg',
        autoQueue: false,
        addRemoveLinks: false,
      },

      locations: [],
      address: {},

      loaded: false,
    };
  },
  computed: {
    editing() { return this.addressId !== null; },
  },
  mounted()
  {
    this
      .load()
      .then( () =>
      {
        console.log( 'loaded' );
        this.loaded = true;

        this.$nextTick( () =>
        {
          console.log( 'nextTick' );
        } );
      } );
  },
  methods: {
    async load()
    {
      console.log( 'loading' );

      const promises = [
        axios.get( route( 'get-all-locations' ) ),
      ];

      if ( this.editing )
        promises.push( axios.get(
          route( 'address.get', { address: this.addressId } ),
        ) );

      const [ locations, address ] = await Promise.all( promises );

      this.locations = _.map( locations.data, v => ( { id: v.id, name: v.name } ) );

      if ( address )
      {
        if ( address.data.success )
          this.address = address.data.model;
        else throw new Error();
      }
    },
    submit( /* event */ )
    {
      console.log( 'submit' );
      // TODO: submit files
      // TODO: file manager while editing
      axios
        .post( this.editing
          ? route( 'address.update', { address: this.addressId } )
          : route( 'address.create' ),
        { ...this.address } )
        .then( ( response ) =>
        {
          if ( response.data.success )
          {
            if ( this.editing )
            {
              toastr.success( 'Updated!' );
              return;
            }

            this.$swal( {
              title: 'Created!',
              text: 'Your address has been added.',
              type: 'success',
              showCancelButton: true,
              cancelButtonColor: '#6c757d',
              cancelButtonText: 'Edit This Address',
              confirmButtonColor: '#17a2b8',
              confirmButtonText: 'Create Another Address',
            } )
              .then( ( result ) =>
              {
                if ( result.value )
                {
                  this.address = {};
                  this.$refs[ 'file-dz' ].removeAllFiles( true );
                }
                else if ( result.dismiss === this.$swal.DismissReason.cancel )
                  window.location.href = route(
                    'address.edit', { address: response.data.model.id },
                  );
              } );
          }
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          _.forIn(
            error.response.data.errors,
            ( errors, field ) => errors
              .forEach( ( e ) => toastr.error( e, changeCase.titleCase( field ) ) ),
          );
        } );
    },
  },
  destroy()
  {
    this.$swal( {
      title: 'Are you sure?',
      text: 'You won\'t be able to revert this!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
    } ).then( ( result ) =>
    {
      if ( result.value )
      {
        axios
          .post( route( 'address.destroy', { address: this.addressId } ) )
          .then( ( response ) =>
          {
            if ( response.data.success )
            {
              this.$swal(
                'Deleted!',
                'Your file has been deleted.',
                'success',
              );
            }
          } )
          .catch( ( error ) =>
          {
            console.log( error );

            if ( error.response.status === 409 )
            {
              this.$swal(
                'Error',
                error.response.data.message,
                'error',
              );
              return;
            }

            this.$swal(
              'Error',
              'Unknown error, check console for more details.',
              'error',
            );
          } );
      }
    } );
  },
};

</script>

<style lang="scss">
  .select2 {
    display: block;
  }

  .select2-container--default .select2-selection--single {
    border-color: #CED4DA;
  }
</style>
