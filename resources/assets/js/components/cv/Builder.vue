<template>
  <div class="container-fluid mt-4 mt-lg-5">
    <template v-if="loaded">
      <button class="btn btn-primary" @click="save">Save</button>
      <pre>{{ cv }}</pre>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
export default {
  data()
  {
    return {
      cv: {},

      loaded: false,
      saving: true,
    };
  },
  computed: {},
  mounted()
  {
    this.load().then( () =>
    {
      this.loaded = true;

      this.$nextTick( () =>
      {
        // DOM finished loading
      } );
    } );
  },
  methods: {
    async load()
    {
      const requests = [
        axios.get( route( 'cv.get.full' ) ),
      ];

      const results = ( await Promise.all( requests ) ).map( r => r.data );

      this.$set( this, 'cv', results[ 0 ] );
    },
    async save()
    {
      this.saving = true;

      try
      {
        const response = await axios.post( route( 'cv.save' ), { cv: this.cv } );
        if ( response.data.success )
          toastr.success( 'Saved' );
      }
      catch ( error )
      {
        console.error( error );
        this.$swal( {
          type: 'error',
          title: 'Error While Saving',
          text: 'There was an error while saving your changes, please try again later.',
          footer: '<small>Try saving again, and if the issue persists, '
            + 'please contact our support team for help.</small>',
        } );
      }
    },
  },
};
</script>

<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  .cv-item {
    background-color: $white;
    padding: 1rem;
    margin: 1rem;
    -webkit-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    -moz-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);

    &-inner {
      display: inline-block;

      .cv-item-education & {
        p:first-child {
          font-size: 1rem;
        }

        p:not(first-child) {
          font-size: 0.85rem;
        }
      }
    }
  }
</style>
