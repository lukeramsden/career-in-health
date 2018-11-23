<template>
  <div class="container mt-lg-5">
    <template v-if="loaded">
      <form @submit.stop.prevent="">
        <div class="card-columns smaller-card-columns">
          <div class="card card-custom">
            <div class="card-body">
              <div class="form-group">
                <label>Title (<span class="text-action">*</span>)</label>
                <input v-model="listing.title"
                       type="text"
                       class="form-control"
                       name="title"
                       maxlength="120"
                       required title="Title">
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <div class="form-group">
                <label>Description</label>
                <textarea v-model="listing.description"
                          :required="!savingForLater"
                          class="form-control"
                          name="description"
                          rows="25"
                          maxlength="3000" title="Description"></textarea>
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <select2 v-model="addressId"
                       :required="!savingForLater"
                       name="address_id">
                <option :value="null">-</option>
                <option v-for="address in addresses"
                        :key="address.id"
                        :value="address.id">{{ address.name }}
                </option>
              </select2>
              <p class="text-muted mb-0 mt-2">
                This will be the address that you want to find staff for.
                If you haven't created an address yet,
                <a :href="route('address.create')" class="text-action">click here</a> to create one.
              </p>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <select2 v-model="listing.job_role"
                       :required="!savingForLater"
                       name="job_role">
                <option :value="null">-</option>
                <option v-for="role in jobRoles"
                        :value="role.id"
                        :key="role.id">
                  {{role.name}}
                </option>
              </select2>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <div v-for="setting in settings"
                   :key="setting.id"
                   class="custom-control custom-radio">
                <input :value="setting.id"
                       v-model="listing.setting"
                       :id="'setting-check-' + setting.id"
                       :required="!savingForLater"
                       type="radio"
                       class="custom-control-input"
                       name="setting" title="Setting">
                <label :for="'setting-check-' + setting.id"
                       class="custom-control-label">{{ setting.name }}</label>
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <div v-for="type in types"
                   :key="type.id"
                   class="custom-control custom-radio">
                <input :value="type.id"
                       v-model="listing.type"
                       :id="'type-check-' + type.id"
                       :required="!savingForLater"
                       type="radio"
                       class="custom-control-input"
                       name="type" title="Type">
                <label :for="'type-check-' + type.id"
                       class="custom-control-label">{{ type.name }}</label>
              </div>
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <label>Minimum/Maximum Salary</label>
              <div id="salary-slider"></div>
              <input id="min-salary-input" v-model="listing.min_salary" type="hidden"
                     name="min_salary">
              <input id="max-salary-input" v-model="listing.max_salary" type="hidden"
                     name="max_salary">
            </div>
          </div>

          <div class="card card-custom">
            <div class="card-body">
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input id="inputSaveForLater"
                         v-model="savingForLater"
                         type="checkbox"
                         class="custom-control-input"
                         name="savingForLater">
                  <label class="custom-control-label" for="inputSaveForLater">
                    Save For Later?</label>
                </div>
              </div>
            </div>
          </div>

          <div v-if="editing" class="card card-custom-material card-custom-material-hover">
            <div class="card-body">
              <div class="form-group">
                <label>Reason</label>
                <input v-model="listing.close_reason"
                       type="text"
                       class="form-control" title="Close Reason">
              </div>
            </div>
            <div class="card-footer p-0">
              <button v-if="listing.closed_at == null"
                      class="btn btn-danger btn-block"
                      @click.stop.prevent="closeListing">
                <span class="oi oi-ban"></span>
                Close
              </button>
              <template v-else>
                <button class="btn btn-primary btn-block"
                        @click.stop.prevent="closeListing">
                  Save Reason
                </button>
                <button class="btn btn-info btn-block mt-0"
                        @click.stop.prevent="openListing">
                  Re-Open
                </button>
              </template>
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
                <a :href="route('job-listing.show', {jobListing:listingId})"
                   class="btn btn-primary">Show</a>
                <a :href="route('job-listing.destroy', {jobListing:listingId})"
                   onclick="return confirm('Are you sure?');"
                   class="btn btn-danger">Delete</a>
              </template>
            </div>
          </div>
        </div>
      </form>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import _          from 'lodash';
import wNumb      from 'wnumb';
import NoUiSlider from 'nouislider';

export default {
  props: {
    listingId: Number,
  },
  data()
  {
    return {
      addresses: [],
      listing: {},

      jobRoles: [],
      settings: [],
      type: [],

      loaded: false,

      salarySlider: null,
    };
  },
  computed: {
    addressId:
      {
        get()
        {
          return this.listingId && this.listing.address ? this.listing.address.id : null;
        },
        set( nval )
        {
          this.$set( this.listing, 'address',
            this.listing.address = _.find( this.addresses,
              ( address ) => address.id === parseInt( nval, 10 ) ) );
        },
      },
    savingForLater: {
      get()
      {
        return !!!( this.listing || {} ).published;
      },
      set( nval )
      {
        this.listing.published = !!!nval;
      },
    },
    editing()
    {
      return this.listingId !== null;
    },
  },
  mounted()
  {
    console.log( 'mounted' );

    this
      .load()
      .then( () =>
      {
        console.log( 'loaded' );
        this.loaded = true;

        this.$nextTick( () =>
        {
          console.log( 'nextTick' );

          this.salarySlider = document.getElementById( 'salary-slider' );

          const moneyFormatter = wNumb( {
            decimals: 0,
            thousand: ',',
            prefix: '£',
          } );

          NoUiSlider.create( this.salarySlider, {
            start: this.listingId ? [
              this.listing.min_salary || 0,
              this.listing.max_salary || 150000,
            ] : [ 0, 150000 ],
            step: 500,
            tooltips: true,
            margin: 2000,
            range: {
              min: 0,
              max: 150000,
            },
            format: wNumb( {
              decimals: 0,
              thousand: ',',
              prefix: '£',
            } ),
            pips: {
              mode: 'positions',
              values: [ 0, 25, 50, 75, 100 ],
              density: 4,
              format: moneyFormatter,
            },
          } );

          this.salarySlider.noUiSlider.on( 'change', ( values /* , handle */ ) =>
          {
            this.$set( this.listing, 'min_salary', moneyFormatter.from( values[ 0 ] ) );
            this.$set( this.listing, 'max_salary', moneyFormatter.from( values[ 1 ] ) );
          } );

          $( '#salary-slider' )
            .find( 'div.noUi-value:nth-child(30)' )
            .html( '£150,000+' );
        } );
      } );
  },
  methods: {
    async load()
    {
      console.log( 'loading' );

      const promises = [
        axios.get( route( 'get-all-job-roles' ) ),
        axios.get( route( 'get-all-listing-settings' ) ),
        axios.get( route( 'get-all-listing-types' ) ),
        axios.get( route( 'company.get-addresses' ) ),
      ];

      if ( this.editing )
        promises.push( axios.get(
          route( 'job-listing.get', { jobListing: this.listingId } ),
        ) );

      const [ jobRoles, settings, types, addresses, listing ] = await Promise.all( promises );

      this.jobRoles = _.map( jobRoles.data, v => ( { id: v.id, name: v.name } ) );
      this.settings = _.map( settings.data, ( name, id ) => ( { id, name } ) );
      this.types    = _.map( types.data, ( name, id ) => ( { id, name } ) );

      this.addresses = addresses.data.models;

      if ( listing )
      {
        if ( listing.data.success )
          this.listing = listing.data.listing;
        else throw new Error();
      }
    },
    submit( /* event */ )
    {
      console.log( 'submit' );
      axios
        .post( this.editing
          ? route( 'job-listing.update', { jobListing: this.listingId } )
          : route( 'job-listing.create' ),
          { ...this.listing, savingForLater: this.savingForLater } )
        .then( ( response ) =>
        {
          if ( response.data.success )
          {
            if ( response.data.redirectTo )
              window.location.href = response.data.redirectTo;

            toastr.success( 'Updated!' );
            if ( _.get( response, 'data.model.published', false ) )
              toastr.info( 'This listing has been published successfully.' );
            else
              toastr.info( 'This listing is not public.' );
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
    closeListing( event )
    {
      console.log( 'closeListing' );
      const $self = $( event.target );
      $self.prop( 'disabled', true );
      axios
        .post( route( 'job-listing.close', { jobListing: this.listingId } ), {
          close_reason: this.listing.close_reason,
        } )
        .then( ( res ) =>
        {
          if ( res.data.success )
            this.listing = res.data.model;
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          toastr.error( 'Could not close listing.' );
        } )
        .then( () =>
        {
          $self.prop( 'disabled', false );
        } );
    },
    openListing( event )
    {
      console.log( 'openListing' );
      const $self = $( event.target );
      $self.prop( 'disabled', true );
      axios
        .post( route( 'job-listing.open', { jobListing: this.listingId } ) )
        .then( ( res ) =>
        {
          if ( res.data.success )
            this.listing = res.data.model;
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          toastr.error( 'Could not open listing.' );
        } )
        .then( () =>
        {
          $self.prop( 'disabled', false );
        } );
    },
  },
};

</script>

<style lang="scss">
  .custom-checkbox .custom-control-label::before,
  .custom-radio .custom-control-label::before {
    border: 1px solid #495057;
  }

  #salary-slider {
    margin: 0 40px 50px 20px;
  }

  .noUi-tooltip {
    display: none;
  }

  .noUi-active .noUi-tooltip {
    display: block;
  }

  .noUi-handle {
    outline: none;
    border-radius: 0;
  }

  .noUi-value-sub {
    color: #999999;
    line-height: 1.8;
  }
</style>
