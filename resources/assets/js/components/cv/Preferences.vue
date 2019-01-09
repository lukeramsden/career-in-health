<template>
  <div class="card card-custom">
    <div class="card-body">
      <div v-if="loaded" class="row align-content-center">
        <div class="col-10 col-lg-9 col-xl-11">
          <h4 class="d-inline-block">Job Preferences</h4>
          <p class="sublabel d-inline-block text-muted">
            Help us match you with your next job
          </p>
        </div>
        <div class="col-2 col-lg-3 col-xl-1">
          <button v-if="!open"
                  class="btn btn-link btn-sm float-right" @click="open = true">
            <span class="oi oi-pencil"></span>
          </button>
          <button v-else
                  class="btn btn-link btn-sm float-right" @click="cancel">
            <span class="oi oi-x"></span>
          </button>
        </div>
        <div class="col-12">
          <div class="cv-item">
            <template v-if="open">
              <form>
                <div class="form-group">
                  <label for="input_job_role">
                    <small><b>Job Role</b></small>
                  </label>
                  <select2 id="input_job_role"
                           v-model="model.job_role"
                           :data="jobRoles"
                           :allow-clear="true"
                           placeholder="-" />
                </div>

                <div class="form-group">
                  <label for="input_job_setting">
                    <small><b>Job Setting</b></small>
                  </label>
                  <select2 id="input_job_setting"
                           v-model="model.setting"
                           :data="settings"
                           :allow-clear="true"
                           placeholder="-" />
                </div>

                <div class="form-group">
                  <label for="input_job_type">
                    <small><b>Job Type</b></small>
                  </label>
                  <select2 id="input_job_type"
                           v-model="model.type"
                           :data="types"
                           :allow-clear="true"
                           placeholder="-" />
                </div>

                <div class="form-group">
                  <small><b>Relocation</b></small>
                  <div class="form-check">
                    <input
                      id="input_willing_to_relocate"
                      v-model="model.willing_to_relocate"
                      class="form-check-input"
                      type="checkbox">
                    <label
                      for="input_willing_to_relocate"
                      class="form-check-label">
                      Willing to relocate
                    </label>
                  </div>
                </div>

                <button :class="{ 'disabled': saving }"
                        :disabled="saving"
                        type="button"
                        class="btn btn-action w-25"
                        @click="save">
                  <loading-icon v-if="saving" />
                  <template v-else>Save</template>
                </button>
              </form>
            </template>
            <template v-else>
              <div class="cv-item-inner">
                <div class="form-group">
                  <label for="job_role">
                    <small><b>Job Role</b></small>
                  </label>
                  <select2 id="job_role" :value="null" disabled>
                    <option :value="null">{{ jobRole }}</option>
                  </select2>
                </div>

                <div class="form-group">
                  <label for="job_setting">
                    <small><b>Job Setting</b></small>
                  </label>
                  <select2 id="job_setting" :value="null" disabled>
                    <option :value="null">{{ setting }}</option>
                  </select2>
                </div>

                <div class="form-group">
                  <label for="job_type">
                    <small><b>Job Type</b></small>
                  </label>
                  <select2 id="job_type" :value="null" disabled>
                    <option :value="null">{{ type }}</option>
                  </select2>
                </div>

                <div class="form-group">
                  <small><b>Relocation</b></small>
                  <div class="form-check">
                    <input
                      id="willing_to_relocate"
                      v-model="model.willing_to_relocate"
                      class="form-check-input"
                      type="checkbox"
                      disabled>
                    <label
                      for="willing_to_relocate"
                      class="form-check-label">
                      Willing to relocate
                    </label>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
      <loading-icon v-else />
    </div>
  </div>
</template>

<script>

/**
 * All the _.toString shite is because for SOME REASON
 * select2 returns integer ID values as strings????
 * doesn't just return the actual damn value I put in for some reason
 * >:(
 */
export default {
  props: {
    value: null,
  },
  data()
  {
    return {
      open: false,
      model: {},
      original: {},

      loaded: false,
      saving: false,

      jobRoles: [],
      settings: [],
      types: [],
    };
  },
  computed: {
    jobRole()
    {
      return ( ( _.find( this.jobRoles,
        [ 'id', _.toString( this.model.job_role ) ] ) || {} ).text ) || '-';
    },
    setting()
    {
      return ( ( _.find( this.settings,
        [ 'id', _.toString( this.model.setting ) ] ) || {} ).text ) || '-';
    },
    type()
    {
      return ( ( _.find( this.types,
        [ 'id', _.toString( this.model.type ) ] ) || {} ).text ) || '-';
    },
  },
  watch: {
    value: {
      handler( val )
      {
        if ( !_.isEqual( val, this.model ) )
        {
          this.$set( this, 'model', JSON.parse( JSON.stringify( this.value ) ) );
        }
      },
      deep: true,
    },
  },
  mounted()
  {
    this.$set( this, 'original', JSON.parse( JSON.stringify( this.value ) ) );
    this.$set( this, 'model', JSON.parse( JSON.stringify( this.value ) ) );

    this.load().then( () =>
    {
      this.loaded = true;
    } );
  },
  methods: {
    async load()
    {
      const [ jobRoles, settings, types ] = await Promise.all( [
        axios.get( route( 'get-all-job-roles' ) ),
        axios.get( route( 'get-all-listing-settings' ) ),
        axios.get( route( 'get-all-listing-types' ) ),
      ] );

      this.jobRoles = _.map( jobRoles.data, ( { id, name: text } ) => ( {
        id: _.toString( id ), text,
      } ) );
      this.settings = _.map( settings.data, ( text, id ) => ( {
        id: _.toString( id ), text,
      } ) );
      this.types    = _.map( types.data, ( text, id ) => ( {
        id: _.toString( id ), text,
      } ) );
    },
    save()
    {
      this.$emit( 'input', this.model );
      this.$set( this, 'original', JSON.parse( JSON.stringify( this.model ) ) );
      this.open = false;
    },
    cancel()
    {
      this.$set( this, 'model', JSON.parse( JSON.stringify( this.original ) ) );
      this.open = false;
    },
  },
};
</script>

<!--suppress CssUnknownTarget -->
<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  .sublabel {
    font-size: 14px;
    padding-left: 5px;
  }
</style>
