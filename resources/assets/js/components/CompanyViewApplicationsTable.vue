<template>
  <div class="container-fluid my-4">
    <template v-if="loaded">
      <div class="row">
        <div class="col-12 col-lg-4 order-lg-last mb-4 mb-lg-0">
          <div id="job-listings-table-filter-card"
               class="card card-custom-material card-custom-material-hover
                position-sticky top-4 mb-3">
            <div class="card-body">
              <input id="input-query" v-model="query.text" class="form-control input-material"
                     placeholder="Search" type="text">
              <v-select id="input-sortBy"
                        v-model="sortBy"
                        :options="sortingDropdown"
                        placeholder="Sort By" />

            </div>
          </div>
          <pagination v-model="page" :last-page="lastPage"
                      class=""
                      custom-input />
          <p class="text-center">Viewing {{ pageItemsCount }} results</p>
        </div>
        <div class="col-12 col-lg-8">
          <div class="table-responsive">
            <table class="table w-100">
              <thead class="thead-primary text-light">
                <tr>
                  <th scope="col">Listing Title</th>
                  <th scope="col">Employee Name</th>
                  <th scope="col">Cover Letter</th>
                  <th scope="col">Status</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <template v-for="result in results">
                  <tr :key="result.id">
                    <td class="text-one-line">{{result.job_listing.title}}</td>
                    <td class="text-one-line">{{result.employee.full_name}}</td>
                    <td v-if="result.custom_cover_letter" class="text-one-line">
                      {{result.custom_cover_letter}}
                    </td>
                    <td v-else class="text-one-line">
                      <span class="text-muted font-italic">No cover letter</span>
                    </td>
                    <td class="text-one-line">{{result.status_name}}</td>
                    <td class="text-one-line"><a :href="result.permalink"
                                                 class="btn btn-action">View</a></td>
                  </tr>
                </template>
              </tbody>
            </table>
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
  filters: {
    dateDiff( val )
    {
      if ( !val ) return '';
      return moment.utc( val ).local().fromNow();
    },
  },
  data()
  {
    return {
      query: {
        text: '',
      },
      sortBy: null,
      loaded: false,
      page: 0,
      lastPage: 0,
      perPage: 10,
      sortingDropdown: [
        { label: 'Default', value: 0 },
        { label: 'Oldest First', value: 1 },
        { label: 'Newest First', value: 2 },
      ],
    };
  },
  computed: {
    ...mapState( 'CompanyViewApplicationsTableModule', {
      items: 'items',
    } ),
    ...mapGetters( {} ),
    matchingResults()
    {
      // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
      const preparedQuery = fuzzaldrin.prepareQuery( this.query.text );
      // We use this to keep track of the similarity for each option.
      const scores        = {};

      return _
        .chain( this.items || [] )
        // eslint-disable-next-line no-unused-vars
        .filter( o =>

          // if (this.query.status)
          //     if (o.status_name !== this.query.status.label)
          //         return false;
          true )
        // Score each option & create a new array out of them.
        .map( ( o ) =>
        {
          // See how well each field compares to the query.
          const fieldScores = [
            o.employee.full_name,
            o.custom_cover_letter,
            o.status_name,
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
      const first = Math.max( this.page * this.perPage + 1, 0 );
      const last  = Math.min( ( this.page * this.perPage ) + this.perPage, this.resultsCount );
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
      .post( route( 'company.application.index.get' ) )
      .then( res =>
      {
        if ( res.data.success )
          this.$store.commit( 'CompanyViewApplicationsTableModule/create', res.data.models );
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
  methods: {},
};
</script>

<!--suppress CssUnknownTarget -->
<style lang="scss">
  @import '~@/_variables.scss';
  @import '~@/_mixins.scss';

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
