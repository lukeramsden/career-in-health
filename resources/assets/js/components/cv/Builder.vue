<template>
  <div class="container-fluid" style="padding-top: 30px">
    <template v-if="loaded">
      <div class="row">
        <div class="col-12 col-lg-6">
          <PreferencesEditor v-model="cv.preferences" />
        </div>
      </div>
      <template v-if="dirty">
        <div class="dirty-actions">
          <button :disabled="saving" class="btn btn-success" @click="save">
            <loading-icon v-if="saving" />
            <template v-else>
              Save
            </template>
          </button>

          <button :disabled="saving" class="btn btn-secondary">
            <loading-icon v-if="saving" />
            <template v-else>
              Save as Draft
            </template>
          </button>

          <button :disabled="saving"
                  class="btn btn-link btn-sm"
                  @click="reset">Reset Changes
          </button>
        </div>
      </template>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import PreferencesEditor from './Preferences.vue';

export default {
  components: {
    PreferencesEditor,
  },
  data()
  {
    return {
      cv: {},

      originalCv: {},
      originalTitle: '',

      loaded: false,
      saving: false,
      dirty: false,
    };
  },
  computed: {},
  watch: {
    dirty( val )
    {
      document.title = val === true ? `(unsaved) ${this.originalTitle}` : this.originalTitle;
    },
  },
  mounted()
  {
    this.originalTitle = document.title;
    this.load().then( () =>
    {
      this.$set( this, 'originalCv', JSON.parse( JSON.stringify( this.cv ) ) );
      this.loaded = true;

      window.onbeforeunload = () =>
        ( this.dirty ? 'If you leave this page you will lose your unsaved changes.' : null );

      this.$watch( 'cv', () =>
      {
        this.dirty = true;
      }, { deep: true } );

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
      const { value } = await this.$swal( {
        title: 'Are you sure?',
        text: 'Your changes will be visible on your profile immediately.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28A745',
        cancelButtonColor: '#DC3545',
        confirmButtonText: 'Yes, save my changes!',
      } );

      if ( !value )
        return;

      this.saving = true;

      try
      {
        const response = await axios.post( route( 'cv.save' ), { cv: this.cv } );
        if ( response.data.success === true )
        {
          this.dirty = false;
          this.$set( this, 'originalCv', JSON.parse( JSON.stringify( this.cv ) ) );
        }
        else throw response;
      }
      catch ( error )
      {
        console.error( error );
        await this.$swal( {
          type: 'error',
          title: 'Error While Saving',
          text: 'There was an error while saving your changes, please try again later.',
          footer: '<small>Try saving again, and if the issue persists, '
            + 'please contact our support team for help.</small>',
        } );
      }

      this.saving = false;
    },
    async reset()
    {
      const { value } = await this.$swal( {
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC3545',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Yes, reset my changes!',
      } );

      if ( value )
      {
        this.$set( this, 'cv', JSON.parse( JSON.stringify( this.originalCv ) ) );
        this.$nextTick( () =>
        {
          this.dirty = false;
        } );
      }
    },
  },
};
</script>

<!--suppress CssUnknownTarget -->
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

  .dirty-actions {
    position: absolute;
    bottom: 15px;
    right: 15px;
  }
</style>
