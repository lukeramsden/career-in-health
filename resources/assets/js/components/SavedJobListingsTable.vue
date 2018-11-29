<template>
  <div class="container-fluid mt-4">
    <div v-if="loaded" id="listing-show-row" class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 col-lg-4 order-lg-last">
            <div id="application-filter-card"
                 class="card card-custom-material card-custom-material-hover position-sticky top-4">
              <div class="card-body p-0">
                <input id="input-query"
                       v-model="query"
                       class="input input-material w-100 p-3"
                       placeholder="Search"
                       type="text">
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8">
            <div v-for="listing in queryResults"
                 :key="listing.id"
                 class="card card-custom card-listing mb-4">
              <div class="card-body">
                <a :href="route('company.show', {company: listing.company.id})"
                   class="card-subtitle">
                  {{listing.company.name}}
                </a>
                <h4 class="card-title">{{listing.job_role.name}}</h4>
                <h5><a
                  :href="permalink(listing)">{{ listing.title }}</a>
                </h5>
                <h6>{{ listing.setting_name }}</h6>
                <div id="small-details">
                  <div>
                    <p><span
                      class="badge badge-secondary badge-pill p-2 px-3">
                      {{ listing.type_name }}
                    </span>
                    </p>
                  </div>
                  <div>
                    <p>
                      {{ listing.min_salary_formatted }}
                      - {{ listing.max_salary_formatted }}
                    </p>
                  </div>
                  <div v-if="listing.closed_at != null">
                    <p>
                      <span class="badge badge-danger p-2 px-3">
                        <span class="oi oi-ban"></span>
                        Closed
                      </span>
                    </p>
                  </div>
                  <div>
                    <p>
                      <a
                        :href="permalink(listing)"
                        class="btn btn-primary btn-sm">View</a>
                      <button
                        class="btn btn-golden btn-sm"
                        @click.stop.prevent="remove(listing.id, $event)"><span
                        class="oi oi-star"></span> Remove
                      </button>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <loading-icon v-else class="mt-5" />
  </div>
</template>

<script>
export default {
  data()
  {
    return { query: '', listings: [], loaded: false };
  },
  computed: {
    queryResults()
    {
      // Don't bother with scoring anything if the query is empty.
      if ( !this.query ) return this.listings;

      // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
      const preparedQuery = fuzzaldrin.prepareQuery( this.query );
      // We use this to keep track of the similarity for each option.
      const scores        = {};

      return this
        .listings
        // Score each option & create a new array out of them.
        .map( ( item ) =>
        {
          // See how well each field compares to the query.
          const fieldScores = [
            item.title,
            item.company.name,
            item.setting_name,
            item.type_name,
            item.job_role.name,
            item.address.name,
            // Creating an array of fields and mapping is easier than writing
            // fz.score(...) six times. Same idea.
            // Scores are a non-normalized number
            // representing how similar the query is to the field.
          ].map(
            field => fuzzaldrin.score( field, this.query, { preparedQuery } ),
          );

          // Store the highest score for this option
          // so we can compare it to other options.
          scores[ item.id ] = Math.max( ...fieldScores );

          return item;
        } )
        // Remove anything with a really low score.
        // You might want to play around with this.
        .filter( item => scores[ item.id ] > 1 )
        // Finally, sort by the highest score.
        .sort( ( a, b ) => scores[ b.id ] - scores[ a.id ] )
      ;
    },
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
      const listings = await axios.post( route( 'employee.saved-job-listings.get' ) );
      if ( listings.data.success )
      {
        this.listings = listings.data.models;
      }
    },
    permalink( listing )
    {
      return route( 'job-listing.show', { jobListing: listing.id } );
    },
    remove( id, event )
    {
      const self  = this;
      const $self = $( event.target );
      $self.prop( 'disabled', true );
      axios
        .post( route( 'employee.unsave-job-listing', { jobListing: id } ) )
        .then( ( response ) =>
        {
          if ( response.data.success )
            self.listings = self.listings.filter( a => a.id !== id );
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          this.$swal(
            'Error',
            'Unknown error, check console for more details.',
            'error',
          );
        } )
        .then( () =>
        {
          $self.prop( 'disabled', false );
        } );
    },
  },
};
</script>
