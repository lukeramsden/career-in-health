<template>
  <div class="container-fluid m-0 p-0">
    <template v-if="loaded">
      <div id="search-row" class="row">
        <div id="search-form-parent" class="col-12 col-md-5 col-lg-4 order-md-last">
          <div id="search-form">
            <form @submit.stop.prevent="">
              <div class="form-group">
                <label for="what-input">What (required)</label>
                <input
                  id="what-input"
                  v-model="query.what"
                  name="what"
                  class="form-control"
                  list="what-list"
                  autocomplete="false">
                <datalist id="what-list">
                  <option v-for="role in jobRoles" :key="role.id">{{role.name}}</option>
                </datalist>
              </div>

              <div class="form-group">
                <label for="where-input">Where (required)</label>
                <select2 id="where-input"
                         v-model="query.where"
                         :data="locations"
                         name="where" />
              </div>

              <div class="form-group form-group-dropdown">
                <label>Radius (miles)</label>
                <div id="radius-slider"></div>
              </div>

              <div class="form-group form-group-dropdown">
                <label>Minimum/Maximum Salary</label>
                <div id="salary-slider"></div>
              </div>

              <div class="form-group">
                <label>Settings</label>

                <div v-for="setting in settings"
                     :key="setting.id"
                     class="custom-control custom-checkbox">
                  <input v-model="query.setting_filter" :value="setting.id"
                         :id="'setting-check-' + setting.id"
                         :title="setting.name" type="checkbox"
                         class="custom-control-input">
                  <label :for="'setting-check-' + setting.id"
                         class="custom-control-label">{{ setting.name }}</label>
                </div>
              </div>

              <div class="form-group">
                <label>Types</label>

                <div v-for="(type) in types"
                     :key="type.id"
                     class="custom-control custom-checkbox">
                  <input v-model="query.type_filter" :value="type.id"
                         :id="'type-check-' + type.id"
                         :title="type.name" type="checkbox"
                         class="custom-control-input">
                  <label :for="'type-check-' + type.id"
                         class="custom-control-label">{{ type.name }}</label>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="search-results-parent" class="col-12 col-md-7 col-lg-8"
             style="background-color: #E6E6E6;">
          <div v-if="!resultsLoaded || totalResults > 0"
               class="card card-custom card-custom-no-top-bar">
            <div v-if="lastPage > 0" class="card-header">
              <p class="float-left mb-0 mt-2">Viewing {{pageItemsCount}} results</p>
              <pagination v-model="page"
                          :disabled="!resultsLoaded"
                          :last-page="lastPage"
                          class="float-right" />
            </div>
            <div class="card-body p-0">
              <div v-if="resultsLoaded" id="search-results">
                <template v-for="(result, index) in results">
                  <div :key="result.id">
                    <div class="card card-custom card-custom-no-top-bar card-listing">
                      <div class="card-body">
                        <a :href="route('company.show', {company: result.company.id})"
                           class="card-subtitle">
                          {{result.company.name}}
                          <verified-badge :company="result.company" />
                        </a>
                        <h4 class="card-title">
                          {{result.job_role.name}}
                        </h4>
                        <h5>
                          <a :href="route('job-listing.show', {jobListing: result.id})">
                            {{ result.title }}
                          </a>
                        </h5>
                        <h6>{{ result.setting_name }}</h6>
                        <div id="small-details">
                          <div>
                            <p>
                              <span class="badge badge-secondary badge-pill p-2 px-3">
                                {{ result.type_name }}
                              </span>
                            </p>
                          </div>
                          <div>
                            <p>
                              <span class="oi oi-map-marker mr-3"></span>
                              {{result.address.location.name}}
                              (<b>{{getDistanceBetween(
                              result.address.location.lat_lng, town.lat_lng)}}</b> miles away)
                            </p>
                          </div>
                          <div>
                            <p>
                              {{result.min_salary | currency}} -
                              {{result.max_salary | currency}}
                            </p>
                          </div>
                          <div>
                            <a :href="route(
                               'tracking.job-listing.search.click', {jobListing: result.id})"
                               class="btn btn-outline-primary">
                              View
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr v-if="index !== results.length - 1">
                  </div>
                </template>
              </div>
              <loading-icon v-else />
            </div>
            <div v-if="lastPage > 0" class="card-footer">
              <pagination v-model="page"
                          :disabled="!resultsLoaded"
                          :last-page="lastPage"
                          class="float-right" />
            </div>
          </div>
          <p v-else class="text-center mt-5">
            <span class="font-italic text-muted">No Results</span>
          </p>
        </div>
      </div>
    </template>
    <loading-icon v-else />
  </div>
</template>

<!--suppress BadExpressionStatementJS -->
<script>
import { mapGetters, mapState } from 'vuex';
import Awesomplete              from 'awesomplete';
import WNumb                    from 'wnumb';
import NoUiSlider               from 'nouislider';

import 'awesomplete/awesomplete.css';
import 'nouislider/distribute/nouislider.css';

import vSelect from 'vue-select';
import Select2 from './Select2.vue';

export default {
  components: {
    vSelect,
    Select2,
  },
  data()
  {
    return {
      query: {
        what: '',
        where: null,
        radius: 50,
        min_salary: 0,
        max_salary: 150000,
        setting_filter: [],
        type_filter: [],
      },
      page: 0,
      lastPage: 0,
      perPage: 10,
      pagesLoaded: [],
      totalResults: 0,

      loaded: false,
      resultsLoaded: true,

      town: null,

      jobRoles: [],
      locations: [],
      settings: [],
      type: [],

      whatDropdown: null,
      radiusSlider: null,
      salarySlider: null,

      /**
       * This is used to track whether the last update to the query
       * object was by onpopstate or not
       */
      statePopped: false,
    };
  },
  computed: {
    ...mapState( 'SearchModule', {
      items: 'items',
    } ),
    ...mapGetters( {} ),
    searchData()
    {
      return {
        ...this.query,
        page: this.page + 1,
      };
    },
    results()
    {
      /**
       * This doesn't account for pages before the current loaded page not
       * actually having been requested
       *
       * We need to store the results based on page number, instead of just
       * in a basic array
       *
       * Scenario: User goes to page, query string specifies page 2
       *
       * Current store: [page2-result1, page2-result2, etc...]
       *
       * Possible replacement: { 2: [result1, result2, etc...] }
       */

      const loadedIndex = this.pagesLoaded.indexOf( this.page );

      if ( loadedIndex === -1 ) return [];

      return this.items.slice( this.perPage * loadedIndex,
        this.perPage * ( loadedIndex + 1 ) );
    },
    pageItemsCount()
    {
      const first = this.page * this.perPage + 1;
      const last  = Math.min( ( this.page * this.perPage )
        + this.perPage, this.totalResults );
      return `${first}-${last} of ${this.totalResults}`;
    },
  },
  mounted()
  {
    this
      .load()
      .then( () =>
      {
        this.loaded = true;

        this.$nextTick( () =>
        {
          const $what       = $( '#what-input' );
          this.whatDropdown = new Awesomplete( '#what-input' );

          const self = this;

          $what.on( 'awesomplete-selectcomplete', () =>
          {
            $what[ 0 ].dispatchEvent( new Event( 'input', { bubbles: true, } ) );
            self.whatDropdown.close();
          } );

          this.radiusSlider = document.getElementById( 'radius-slider' );

          NoUiSlider.create( this.radiusSlider, {
            start: [ this.query.radius ],
            step: 5,
            tooltips: true,
            range: {
              min: 5,
              max: 50,
            },
            pips: {
              mode: 'steps',
              density: 3,
            },
            format: WNumb( {
              decimals: 0,
            } ),
          } );

          this.radiusSlider.noUiSlider.on( 'change', function ()
          {
            self.query.radius = parseInt( this.get(), 10 );
          } );

          $( '#radius-slider' )
            .find( 'div.noUi-value:nth-child(47)' )
            .html( '50+' );

          this.salarySlider     = document.getElementById( 'salary-slider' );
          const salaryFormatter = WNumb( {
            decimals: 0,
            thousand: ',',
            prefix: '£',
          } );

          NoUiSlider.create( this.salarySlider, {
            start: [ this.query.min_salary, this.query.max_salary ],
            step: 500,
            tooltips: true,
            margin: 2000,
            range: {
              min: 0,
              max: 150000,
            },
            format: WNumb( {
              decimals: 0,
              thousand: ',',
              prefix: '£',
            } ),
            pips: {
              mode: 'positions',
              values: [ 0, 25, 50, 75, 100 ],
              density: 4,
              format: salaryFormatter,
            },
          } );

          this.salarySlider.noUiSlider.on( 'change', ( values ) =>
          {
            self.query.min_salary = salaryFormatter.from( values[ 0 ] );
            self.query.max_salary = salaryFormatter.from( values[ 1 ] );
          } );

          /**
           * We add the watchers here to stop them being called when we
           * parse the query string
           */
          this.$watch( 'query', function ( nval )
          {
            if ( nval.what && nval.where ) this.resultsLoaded = false;

            this.page         = 0;
            this.pagesLoaded  = [];
            this.totalResults = 0;
            this.$store.commit( 'SearchModule/clear' );
            this.search();

            if ( this.radiusSlider )
              this
                .radiusSlider.noUiSlider
                .set( [ nval.radius ] );

            if ( this.salarySlider )
              this
                .salarySlider.noUiSlider
                .set( [ nval.min_salary, nval.max_salary ] );
          }, { deep: true, } );

          this.$watch( 'page', function ( nval )
          {
            if ( !this.pagesLoaded.includes( nval ) )
            {
              this.resultsLoaded = false;
              this.search();
            }

            this.updateQueryString();
          } );

          this.search();
        } );
      } )
      .catch( e => console.error( e ) );
  },
  destroyed()
  {
    $( '#what-input' ).off();
    this.whatDropdown.destroy();
    this.whatDropdown = null;

    $( '#radius-slider' ).off();
    this.radiusSlider.noUiSlider.destroy();
    this.radiusSlider = null;

    $( '#salary-slider' ).off();
    this.salarySlider.noUiSlider.destroy();
    this.salarySlider = null;
  },
  methods: {
    route( ...args )
    {
      return route( ...args );
    },
    async load()
    {
      const [ jobRoles, locations, settings, types ] = await Promise.all( [
        axios.get( route( 'get-all-job-roles' ) ),
        axios.get( route( 'get-all-locations' ) ),
        axios.get( route( 'get-all-listing-settings' ) ),
        axios.get( route( 'get-all-listing-types' ) ),
      ] );

      this.jobRoles  = _.map( jobRoles.data, v => ( { id: v.id, name: v.name, } ) );
      this.locations = _.map( locations.data, v => ( { id: v.id, text: v.name, } ) );
      this.settings  = _.map( settings.data, ( name, id ) => ( { id, name, } ) );
      this.types     = _.map( types.data, ( name, id ) => ( { id, name, } ) );

      {
        const before = _.cloneDeep( this.query );
        this.parseQueryString();
        const after        = _.cloneDeep( this.query );
        this.resultsLoaded = _.isEqual( before, after );
      }
    },
    updateQueryString()
    {
      const urlParams = new URLSearchParams( location.search );

      Object.keys( this.query ).forEach( ( x ) =>
      {
        const val = this.query[ x ];
        if ( x || x === 0 )
        {
          if ( _.isArray( val ) )
          {
            urlParams.delete( x );

            if ( val.length > 0 )
            {
              val.forEach( ( y ) => urlParams.append( x, y ) );
            }
          }
          else urlParams.set( x, val );
        }
      } );

      if ( this.page >= 0 ) urlParams.set( 'page', this.page + 1 );

      if ( !this.statePopped )
      {
        window.history.pushState( {}, '',
          decodeURIComponent( `${location.pathname}?${urlParams}` ) );
      }
      else this.statePopped = false;

      window.onpopstate = () =>
      {
        this.statePopped = true;
        this.parseQueryString();
      };
    },
    parseQueryString()
    {
      const urlParams = new URLSearchParams( location.search );

      Object.keys( this.query ).forEach( ( x ) =>
      {
        this.$set( this.query, x,
          ( _.isArray( this.query[ x ] )
            ? urlParams.getAll( x )
            : urlParams.get( x ) ) || this.query[ x ] );
      } );

      this.page = ( _.toSafeInteger( urlParams.get( 'page' ) ) || 1 ) - 1;
    },
    search: _.debounce( function ()
    {
      if ( !this.query.what || !this.query.where ) return;

      this.resultsLoaded = false;

      this.updateQueryString();

      axios
        .post( route( 'search.get' ), this.searchData )
        .then( res =>
        {
          if ( res.data.success )
          {
            this.$store.commit( 'SearchModule/create', res.data.models.results.data );

            this.pagesLoaded.push( this.page );

            this.lastPage     = res.data.models.results.last_page - 1;
            this.perPage      = res.data.models.results.per_page;
            this.totalResults = res.data.models.results.total;

            this.town = res.data.models.town;
          }
          else throw new Error( 'Could not load listings.' );
        } )
        .catch( e => console.error( e ) )
        .then( () =>
        {
          this.$nextTick( () => this.resultsLoaded = true );
        } );
    }, 800, { leading: true, tailing: true, } ),
    /**
     * Get distance between 2 lat/lng points in miles
     * Either takes 2 objects of type LatLng, or 4 arguments of type number
     *
     * Option 1:
     *
     * LatLng {
             *     lat: number
             *     lng: number,
             * }
     *
     * latlng1, latlng2
     *
     * Option 2:
     *
     * lat1, lng1, lat2, lng2
     */
    getDistanceBetween( ...args )
    {
      const calc = ( lat1, lon1, lat2, lon2 ) =>
      {
        const deg2rad = d => d * 0.017453292519943295; // Math.PI / 180
        const dLat    = deg2rad( lat2 - lat1 ) / 2;
        const dLon    = deg2rad( lon2 - lon1 ) / 2;
        const a       = Math.sin( dLat ) * Math.sin( dLat )
          + Math.cos( deg2rad( lat1 ) ) * Math.cos( deg2rad( lat2 ) )
          * Math.sin( dLon ) * Math.sin( dLon )
        ;
        // convert to miles
        return ( 6371 * ( 2 * Math.atan2(
          Math.sqrt( a ), Math.sqrt( 1 - a ),
        ) ) ) * 0.6213711922;
      };

      // Option 1
      if ( args.length === 2 )
      {
        return calc(
          args[ 0 ].lat, args[ 0 ].lng,
          args[ 1 ].lat, args[ 1 ].lng,
        ).toFixed( 1 );
      }
      // Option 2
      if ( args.length === 4 )
      {
        return calc( ...args ).toFixed( 1 );
      }
      throw new Error( 'Wrong number of arguments.' );
    },
  },
};
</script>

<style lang="scss">
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

  .awesomplete {
    display: block;
  }
</style>
