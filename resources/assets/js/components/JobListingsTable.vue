<template>
  <div class="container-fluid my-4">
    <template v-if="loaded">
      <div class="row">
        <div class="col-12 col-lg-4 order-lg-last">
          <div id="job-listings-table-filter-card"
               class="card card-custom-material card-custom-material-hover position-sticky top-4">
            <div class="card-body">
              <input id="input-query" v-model="query.text" class="form-control input-material"
                     placeholder="Search" type="text">
              <v-select id="input-status" :allow-clear="true"
                        v-model="query.status"
                        :options="statusDropdown"
                        placeholder="Status"
                        class="" />
              <v-select id="input-address" v-model="query.address"
                        :options="addressDropdown"
                        placeholder="Address" class=""
                        multiple />
              <v-select id="input-sortBy" v-model="sortBy"
                        :options="sortingDropdown"
                        placeholder="Sort By"
                        class="" />
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-8">
          <div id="job-listings-table-results" class="card card-custom-material">
            <div class="card-header text-muted">
              <p class="float-left mb-0 mt-2">Viewing {{pageItemsCount}} results</p>
              <pagination v-model="page" :last-page="lastPage" custom-input
                          class="float-right" />
            </div>
            <template v-for="result in results">
              <li :key="result.id" class="list-group-item">
                <div class="row">
                  <div class="col-12 col-lg-9">
                    <p class="mb-1">{{result.title}}</p>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="btn-group-vertical btn-group-full">
                      <a :href="viewListing(result)" class="btn btn-primary">View</a>
                      <a :href="editListing(result)" class="btn btn-action">Edit</a>
                    </div>
                  </div>
                  <div class="col-12">
                    <div id="small-details">
                      <div>
                        <p>
                          <span v-if="result.status_name === 'Published'"
                                class="badge badge-primary badge-pill p-2 px-3">
                            <span class="oi oi-check"></span>
                            Published
                          </span>
                          <span v-else-if="result.status_name === 'Closed'"
                                class="badge badge-danger badge-pill p-2 px-3">
                            <span class="oi oi-ban"></span>
                            Closed <moments-ago :date="result.closed_at" suffix="ago" />
                          </span>
                          <span v-else-if="result.status_name === 'Draft'"
                                class="badge badge-secondary badge-pill p-2 px-3">
                            <span class="oi oi-bookmark"></span>
                            Draft
                          </span>
                          <span v-else
                                class="badge badge-info badge-pill p-2 px-3">
                            {{ result.status_name }}
                          </span>
                        </p>
                      </div>
                      <div>
                        <p v-if="result.address">
                          <span class="oi oi-map-marker mr-1"></span>
                          {{ result.address.name }}
                        </p>
                      </div>
                      <div>
                        <p>
                          <span class="oi oi-calendar"></span>
                          <span class="text-muted">
                            Last Updated</span> <moments-ago
                              :date="result.last_edited" suffix="ago" />
                        </p>
                      </div>
                      <div>
                        <p>
                          <span class="oi oi-calendar"></span>
                          <span class="text-muted">
                            Created</span> <moments-ago
                              :date="result.created_at" suffix="ago" />
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </template>
          </div>
        </div>
      </div>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';
import vSelect                  from 'vue-select';

function getSortFunction( idx )
{
  switch ( idx )
  {
  case 1:
    return ( a, b ) =>
      moment.utc( a.created_at ).isAfter( b.created_at );
  case 2:
    return ( a, b ) =>
      moment.utc( a.created_at ).isBefore( b.created_at );
  default:
    return () => 0;
  }
}

export default {
  components: {
    vSelect,
  },
  data()
  {
    return {
      query: {
        text: '',
        status: null,
        address: [],
      },
      sortBy: null,
      loaded: false,
      page: 0,
      lastPage: 0,
      perPage: 10,
      statusDropdown: [
        { label: 'Draft', value: 0 },
        { label: 'Published', value: 1 },
        { label: 'Closed', value: 2 },
      ],
      sortingDropdown: [
        { label: 'Default', value: 0 },
        { label: 'Oldest First', value: 1 },
        { label: 'Newest First', value: 2 },
      ],
    };
  },
  computed: {
    ...mapState( 'JobListingsTableModule', {
      listings: 'items',
    } ),
    ...mapGetters( {} ),
    addressDropdown()
    {
      return _
        .chain( this.listings || [] )
        .map( o =>
          ( { label: ( o.address || {} ).name, value: ( o.address || {} ).id } ) )
        .uniqBy( 'value' )
        .value()
      ;
    },
    matchingResults()
    {
      // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
      const preparedQuery = fuzzaldrin.prepareQuery( this.query.text );
      // We use this to keep track of the similarity for each option.
      const scores        = {};

      return _
        .chain( this.listings || [] )
        .filter( o =>
        {
          if ( this.query.status )
            if ( o.status_name !== this.query.status.label )
              return false;

          if ( this.query.address.length > 0 )
            if ( !_.includes( _.map( this.query.address, 'value' ), o.address.id ) )
              return false;

          return true;
        } )
        // Score each option & create a new array out of them.
        .map( ( o ) =>
        {
          // See how well each field compares to the query.
          const fieldScores = [
            o.title,
            // o.description,
          ].map( field => fuzzaldrin.score( field, this.query.text, { preparedQuery } ) );

          scores[ o.id ] = Math.max( ...fieldScores );
          return o;
        } )
        // Remove anything with a really low score.
        .filter( o => ( this.query.text ? scores[ o.id ] > 1 : true ) )
        .value()
        // Finally, sort by the highest score.
        .sort( ( a, b ) => scores[ b.id ] - scores[ a.id ] )
      ;
    },
    results()
    {
      return _
        .chain( this.matchingResults )
        .thru( arr =>
          ( this.sortBy ? arr.slice().sort( getSortFunction( this.sortBy.value ) ) : arr ) )
        .chunk( this.perPage )
        .nth( this.page )
        .value();
    },
    resultsCount()
    {
      return this.matchingResults.length;
    },
    pageItemsCount()
    {
      const first = Math.max( 0, this.page * this.perPage + 1 );
      const last  = Math.max( 0,
        Math.min( ( this.page * this.perPage ) + this.perPage, this.resultsCount ) );
      return `${first}-${last} of ${this.resultsCount}`;
    },
  },
  watch: {
    matchingResults( newVal )
    {
      if ( !newVal ) return;
      this.lastPage = Math.ceil( newVal.length / this.perPage ) - 1;
      this.page     = _.clamp( 0, this.lastPage );
    },
  },
  mounted()
  {
    axios
      .post( route( 'job-listing.index.get' ) )
      .then( res =>
      {
        if ( res.data.success )
          this.$store.commit( 'JobListingsTableModule/create', res.data.models );
        else
          throw new Error( 'Could not load listings.' );
      } )
      .catch( e =>
      {
        console.error( e );
        toastr.error( 'Could not load listings.' );
      } )
      .then( () =>
      {
        this.loaded = true;
      } );
  },
  methods: {
    viewListing( listing )
    {
      return route( 'job-listing.show', { jobListing: listing.id } );
    },
    editListing( listing )
    {
      return route( 'job-listing.edit', { jobListing: listing.id } );
    },
  },
};
</script>

<!--suppress CssUnknownTarget -->
<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  #small-details {
    display: block;
    margin: 0;

    p {
      margin: 0;
    }

    div {
      margin-top: 0.8rem;
      display: inline-block;

      &:not(:first-child) {
        margin-left: 1rem;
      }
    }
  }

  .dropdown.v-select {
    border: none;
    border-radius: 4px;
    color: #495057;

    .dropdown-toggle {
      background-color: #FFFFFF !important;
      padding: 1rem !important;
      border: none !important;
      /*border-radius: 0;*/

      &::after {
        content: unset;
        display: none;
      }
    }

    input[type="search"], input[type="search"]:focus, .selected-tag {
      margin: 0 !important;
      padding: 0 !important;
      border: none !important;
    }

    input[type="search"]::placeholder {
      color: #999999 !important;
    }

    .selected-tag {
      background-color: #F9834E !important;
      padding: 0.3rem 0.6rem !important;
      color: #FFFFFF !important;
      margin-right: 0.15rem !important;
      margin-bottom: 0.15rem !important;
    }

    &.single {
      .selected-tag {
        background-color: transparent !important;
        color: #333333 !important;
        margin: 0 !important;
        padding: 0 !important;
      }
    }

    &::placeholder {
      color: #999999;
    }
  }

  #job-listings-table-filter-card {
    .card-body {
      padding: 0 !important;

      & > * {
        border: none;

        &:first-child {
          &, & > .dropdown-toggle {
            border-radius: 0;
            border-bottom: 1px solid #CED4DA;
          }
        }

        &:last-child {
          &, & > .dropdown-toggle {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
          }
        }

        &:not(:first-child):not(:last-child) {
          &, & > .dropdown-toggle {
            border-radius: 0;
            border-bottom: 1px solid #CED4DA;
          }
        }
      }

      .input-material {
        padding: 1rem;
        margin: 0 !important;
        width: 100% !important;

        &::placeholder {
          color: #999999;
        }
      }
    }
  }
</style>
