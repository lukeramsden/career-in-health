<template>
  <div class="container-fluid" style="padding-top: 30px">
    <template v-if="loaded">
      <div class="row">
        <div class="col-12 mb-4 col-lg-6 mb-lg-0">
          <PreferencesEditor :value="cv.preferences"
                             @input="subSaved('preferences', $event)" />
        </div>
        <div class="col-12 mb-4 col-lg-6 mb-lg-0">
          <EducationEditor :value="cv.education"
                           @input="subSaved('education', $event)" />
        </div>
      </div>
      <div class="dirty-actions">
        <div v-if="savingDraft" id="autosaving-indicator" class="btn btn-link disabled">
          <p>Auto-saving draft...</p>
          <loading-icon class="d-inline-block" style="width: 20px; height: 20px;" />
        </div>

        <div v-if="lastSaved" id="last-saved-at" class="btn btn-link disabled">
          <moments-ago :date="lastSaved"
                       prefix="Last saved"
                       suffix="ago" />
        </div>

        <template v-if="dirty">
          <button :disabled="saving" class="btn btn-action" @click="save">
            <loading-icon v-if="saving" />
            <template v-else>
              Save
            </template>
          </button>

          <button :disabled="saving"
                  class="btn btn-link btn-sm"
                  @click="reset">Reset Changes
          </button>
        </template>
      </div>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import PreferencesEditor from './Preferences.vue';
import EducationEditor   from './Education.vue';

export default {
  components: {
    PreferencesEditor,
    EducationEditor,
  },
  data()
  {
    return {
      cv: {
        draft: {},
      },

      originalCv: {},
      originalTitle: '',

      loaded: false,
      saving: false,
      savingDraft: false,
      dirty: false,
      lastSaved: null,
    };
  },
  watch: {
    dirty( val )
    {
      document.title = val === true ? `(unsaved) ${this.originalTitle}` : this.originalTitle;
    },
  },
  mounted()
  {
    this.originalTitle = document.title;
    this.load().then( async () =>
    {
      this.$set( this, 'originalCv',
        _.omit(
          JSON.parse(
            JSON.stringify( this.cv ),
          ), [ 'draft' ],
        ) );

      if ( !_.isEmpty( this.cv.draft ) )
      {
        const { value } = await this.$swal( {
          title: 'Load Draft Version?',
          text: 'You have unpublished changes from last time you were here,'
            + ' would you like to load them?',
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#455782',
          cancelButtonColor: '#DC3545',
          confirmButtonText: 'Yes, load draft version.',
          cancelButtonText: 'No, delete the draft version',
        } );

        if ( value )
        {
          // load draft
          this.$set( this, 'cv', _.omit( JSON.parse( this.cv.draft ), [ 'draft' ] ) );
          this.dirty = true;
        }
        else
          this.deleteDraft();
      }

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
    async deleteDraft()
    {
      // delete draft
      this.$set( this.cv, 'draft', null );
      try
      {
        const response = await axios.post( route( 'cv.delete.draft' ) );
        if ( !response.data.success )
          console.error( response );
      }
      catch ( e )
      {
        console.error( e );
      }
    },
    subSaved( prop, event )
    {
      this.$set( this.cv, prop, event );
      this.saveDraft();
    },
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
          this.$set( this, 'originalCv',
            _.omit(
              JSON.parse(
                JSON.stringify( this.cv ),
              ), [ 'draft' ],
            ) );
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

      this.saving    = false;
      this.lastSaved = moment().format();
    },
    async saveDraft()
    {
      if ( this.savingDraft )
        return;

      console.log( 'Builder:saveDraft' );
      this.savingDraft = true;

      try
      {
        const response = await axios.post( route( 'cv.save.draft' ), { cv: this.cv } );
        if ( response.data.success === true )
        {
          if ( response.status === 200 )
            this.$set( this.cv, 'draft', JSON.stringify( _.omit( this.cv, [ 'draft' ] ) ) );
          else if ( response.status === 202 )
            this.$set( this.cv, 'draft', null );
        }
        else throw response;
      }
      catch ( error )
      {
        console.error( error );
        toastr.error( 'Error while auto-saving draft.' );
      }

      this.savingDraft = false;
      this.lastSaved   = moment().format();
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
        this.deleteDraft();
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

  #autosaving-indicator {
    p {
      display: inline-block;
      vertical-align: middle;
      margin-bottom: 0;
    }
  }
</style>
