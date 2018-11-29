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
              <dropzone id="dropzone"
                        ref="fileUploader"
                        :options="dzOpts"
                        @vdropzone-mounted="dzmounted"
                        @vdropzone-file-added="dzfileAdded"
                        @vdropzone-removed-file="dzremoved" />
            </div>
          </div>

          <div class="card card-custom-material card-custom-material-hover card-custom-no-top-bar">
            <div class="btn-group btn-group-full btn-group-vertical">
              <button type="submit"
                      class="btn btn-action"
                      @click="submit">
                <loading-icon v-if="saving" />
                <template v-else>
                  {{ editing ? 'Save' : 'Create' }}
                </template>
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
        parallelUploads: 4,
        maxFiles: 20,
        maxFilesize: 10,
        acceptedFiles: 'image/png,image/jpeg',
        autoQueue: false,
        addRemoveLinks: true,
        paramName: 'images',
      },

      locations: [],
      address: {},

      loaded: false,
      saving: false,
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
      if ( this.saving ) return;

      console.log( 'submit' );

      this.saving = true;

      if ( !this.editing )
        this.$set(
          this.address,
          'images',
          this.$refs.fileUploader.dropzone.getAcceptedFiles(),
        );

      const formData = new FormData();
      Object.keys( this.address ).forEach( ( key ) =>
      {
        const val = this.address[ key ];
        if ( _.isArray( val ) )
          Object.keys( val ).map( ( i ) => formData.append( `${key}[]`, val[ i ] ) );
        else
          formData.append( key, val || '' );
      } );

      axios
        .post( this.editing
          ? route( 'address.update', { address: this.addressId } )
          : route( 'address.create' ), formData )
        .then( ( response ) =>
        {
          if ( response.data.success )
          {
            if ( this.editing )
            {
              toastr.success( 'Updated!' );
              return;
            }

            this
              .$swal( {
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
                  this.$refs.fileUploader.removeAllFiles( true );
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
        } )
        .then( () => this.saving = false );
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
                ).then( ( result2 ) =>
                {
                  if ( result2.value )
                    window.location.href = route( 'address.index' );
                } );
              }
            } )
            .catch( ( error ) =>
            {
              console.error( error );

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
    dzmounted()
    {
      ( this.address.images || [] ).map(
        ( val ) => this.$refs.fileUploader.manuallyAddFile(
          {
            id: val.id,
            name: val.name,
            size: val.size,
            type: val.mime_type,
          }, val.url,
        ),
      );
    },
    dzfileAdded( file )
    {
      if ( !this.editing ) return;

      const vm = this;
      const dz = vm.$refs.fileUploader.dropzone;

      const formData = new FormData();
      formData.append( 'image', file );

      if ( !file.id )
      {
        dz.emit( 'sending', file, undefined, formData );
        axios
          .post( route( 'address.image.store', { address: vm.addressId } ),
            formData, {
              // config
              onUploadProgress: progressEvent =>
              {
                const percentCompleted = Math.floor(
                  ( progressEvent.loaded * 100 ) / progressEvent.total,
                );
                dz.emit( 'uploadprogress', file, percentCompleted, progressEvent.loaded );
                console.log( `progress: ${file.name} is ${percentCompleted}%`
                  + `(${progressEvent.loaded}/${progressEvent.total}) complete` );
              },
            } )
          .then( ( response ) =>
          {
            console.log();
            file.id = _.get( response, 'data.model.id' );
            dz.emit( 'success', file, response );
            dz.emit( 'complete', file );
          } )
          .catch( ( error ) =>
          {
            console.log( error );
            dz.emit( 'error', file, 'Error' );
            _.forIn(
              error.response.data.errors,
              ( errors, field ) => errors.forEach(
                ( e ) => toastr.error( e, changeCase.titleCase( field ) ),
              ),
            );
          } );
      }
    },
    dzremoved( file )
    {
      console.log( file );
      if ( file.id )
      // remove file on server
        axios
          .post( route( 'media.destroy', { media: file.id } ) )
          .then( () => { } )
          .catch( ( error ) =>
          {
            console.log( error );
            _.forIn(
              error.response.data.errors,
              ( errors,
                field ) => errors.forEach(
                ( e ) => toastr.error( e, changeCase.titleCase( field ) ),
              ),
            );
          } );
    },
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

  /*.vue-dropzone .dz-preview .dz-remove {*/
  /*width: 85%;*/
  /*}*/
</style>
