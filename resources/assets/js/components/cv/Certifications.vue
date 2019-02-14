<template>
  <div class="card card-custom">
    <div class="card-body">
      <div v-if="loaded" class="row align-content-center">
        <div class="col-10 col-lg-9 col-xl-11">
          <h4 class="d-inline-block mb-3">Certifications and Licenses</h4>
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
          <template v-if="open">
            <div v-for="(model, idx) in models" :key="idx" class="cv-item">
              <form v-if="!model.collapsed" class="cv-item-multiple-form" @submit.prevent="">
                <div class="form-group mb-0">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="input_title">Title</label>

                      <input
                        id="input_title"
                        v-model="model.title"
                        type="text"
                        class="form-control"
                        maxlength="150"
                        required>
                    </div>

                    <div class="col-12 mb-3 col-xl-6">
                      <label>Start Date</label>
                      <datepicker id="input_start_date"
                                  :value="model.start_date"
                                  :bootstrap-styling="true"
                                  :required="true"
                                  :disabled-dates="model.startDateDisabled"
                                  format="MMMM yyyy"
                                  minimum-view="month"
                                  @input="input(model,'start_date',$event)" />
                    </div>

                    <div class="col-12 mb-3 col-xl-6">
                      <label>Expiration Date</label>
                      <datepicker id="input_end_date"
                                  :value="model.end_date"
                                  :bootstrap-styling="true"
                                  :clear-button="true"
                                  :disabled-dates="model.endDateDisabled"
                                  format="MMMM yyyy"
                                  minimum-view="month"
                                  @input="input(model,'end_date',$event)" />
                      <small
                        id="input_end_date_help_block"
                        class="form-text text-muted">
                        Leave empty if this certification or license does not expire.
                      </small>
                    </div>

                    <div class="col-12 mb-3">
                      <label for="input_description">Description</label>

                      <textarea
                        id="input_description"
                        v-model="model.description"
                        class="form-control"
                        cols="30"
                        rows="5"
                        maxlength="500"
                        aria-describedby="input_description_help_block"></textarea>

                      <small
                        id="input_description_help_block"
                        class="form-text text-muted">
                        A small description of your time here.
                      </small>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-link float-right" @click="removeItem(idx)">
                        Delete <span class="oi oi-trash"></span>
                      </button>
                    </div>

                    <div class="col-12">
                      <div class="item-collapse"
                           @click="collapsed(true, idx)">
                        <span class="oi oi-collapse-up"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div v-else class="cv-item-multiple-form">
                <p>
                  {{model.title || '-'}}
                  <span class="text-muted">from</span>
                  {{ formatDate(model.start_date, 'MMMM YYYY') || '-' }}
                </p>
                <div class="item-uncollapse"
                     @click="collapsed(false, idx)">
                  <span class="oi oi-collapse-down"></span>
                </div>
              </div>

              <hr class="mx-5">
            </div>

            <button :class="{ 'disabled': saving }"
                    :disabled="saving"
                    type="button"
                    class="btn btn-action w-25"
                    @click="save">
              <loading-icon v-if="saving" />
              <template v-else>Save</template>
            </button>

            <button class="btn btn-link btn-block"
                    @click="addNew">
              <span class="oi oi-plus"></span>
            </button>
          </template>
          <template v-else>
            <div v-for="(model, idx) in models" :key="idx" class="cv-item">
              <div class="cv-item-inner ml-md-3">
                <p v-if="model.title" class="my-1">
                  {{ model.title }}
                </p>
                <p v-if="model.location" class="my-1">{{ model.location }}</p>
                <template v-if="validDate(model.end_date)">
                  <p class="my-1">
                    {{ formatDate(model.start_date, 'MMMM YYYY') }}
                    to
                    {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>
                </template>
                <template v-else>
                  <p v-if="model.start_date" class="my-1">
                    Started {{ formatDate(model.start_date, 'MMMM YYYY') }}
                  </p>
                </template>
                <p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>
                <a :href="model.url" class="btn btn-link">View File</a>
              </div>
              <hr class="mx-5">
            </div>
          </template>
        </div>
      </div>
      <loading-icon v-else />
    </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
  components: {
    Datepicker,
  },
  props: {
    value: null,
  },
  data()
  {
    return {
      open: false,
      models: [],
      original: {},

      loaded: false,
      saving: false,
    };
  },
  watch: {
    value: {
      handler( val )
      {
        if ( !_.isEqual( val, this.models ) )
          this.$set( this, 'models', JSON.parse( JSON.stringify( this.value ) ) );
      },
      deep: true,
    },
  },
  mounted()
  {
    this.$set( this, 'original', JSON.parse( JSON.stringify( this.value ) ) );
    this.$set( this, 'models', JSON.parse( JSON.stringify( this.value ) ) );

    this.models.forEach( model =>
    {
      model.startDateDisabled = {
        customPredictor: ( date ) =>
          model.end_date
          && moment( date ).isSameOrAfter( model.end_date, 'M' ),
      };
      model.endDateDisabled   = {
        customPredictor: ( date ) =>
          model.start_date
          && moment( date ).isSameOrBefore( model.start_date, 'M' ),
      };
    } );

    this.load().then( () =>
    {
      this.loaded = true;
    } );
  },
  methods: {
    async load()
    {
      // const [ ] = await Promise.all( [ ] );
    },
    save()
    {
      this.$emit( 'input', this.models );
      this.$set( this, 'original', JSON.parse( JSON.stringify( this.models ) ) );
      this.open = false;
    },
    cancel()
    {
      this.$set( this, 'models', JSON.parse( JSON.stringify( this.original ) ) );
      this.open = false;
    },
    addNew()
    {
      this.models.push( { startDateDisabled: {}, endDateDisabled: {} } );
    },
    removeItem( idx )
    {
      this.$delete( this.models, idx );
    },
    collapsed( b, idx )
    {
      this.$set( this.models, idx, { ...this.models[ idx ], collapsed: b } );
    },
    input( model, field, event )
    {
      this.$set( model.startDateDisabled, 'customPredictor', ( date ) =>
        model.end_date
        && moment( date ).isSameOrAfter( model.end_date, 'M' ) );

      this.$set( model.endDateDisabled, 'customPredictor', ( date ) =>
        model.start_date
        && moment( date ).isSameOrBefore( model.start_date, 'M' ) );

      if ( _.isNull( event ) )
      {
        if ( field === 'end_date' )
          model[ field ] = null;
        else
          toastr.error( 'Start date cannot be null.' );

        return;
      }

      const m = moment( event );
      if ( !m.isValid() )
      {
        toastr.error( 'Invalid date' );
        return;
      }

      if ( field === 'start_date'
        && model.end_date
        && m.isAfter( model.end_date ) )
      {
        toastr.error( 'Start date must be before end date.' );
        return;
      }

      if ( field === 'end_date'
        && model.start_date
        && m.isBefore( model.start_date ) )
      {
        toastr.error( 'End date must be after start date.' );
        return;
      }

      model[ field ] = m.format( 'Y-MM-DD [00:00:00]' );
    },
    formatDate: ( date, format ) => moment( date || '' ).format( format ),
    validDate: ( date ) => moment( date || '' ).isValid(),
  },
};
</script>

<!--suppress CssUnknownTarget -->
<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  .row > div {
    padding-left: 7px;
    padding-right: 7px;
  }

  .cv-item-multiple-form {
    background-color: lighten($gray-200, 4%);
    padding: 7px 9px;
    border-radius: 3px;

    .form-group .row {
      padding: 7px 9px;
    }
  }

  .item-collapse, .item-uncollapse {
    width: 100%;
    cursor: pointer;
    border: #CED4DA solid 1px;
    text-align: center;
  }
</style>

<style lang="scss">
  .vdp-datepicker > .input-group > .form-control {
    background-color: #FFFFFF !important;
  }
</style>
