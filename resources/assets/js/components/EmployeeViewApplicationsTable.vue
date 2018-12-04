<template>
  <div class="container-fluid mt-4">
    <div v-if="loaded" id="listing-show-row" class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 col-lg-4 order-lg-last">
            <div id="application-filter-card"
                 class="card card-custom-material card-custom-material-hover position-sticky top-4">
              <div class="card-body p-0">
                <input id="input-query" v-model="query" class="input input-material w-100 p-3"
                       placeholder="Search" type="text">
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8">
            <div class="table-responsive">
              <table class="table w-100">
                <thead class="thead-primary text-light">
                  <tr>
                    <th scope="col">Listing Title</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Cover Letter</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="result of queryResults" :key="result.id">
                    <td class="text-one-line">{{result.job_listing.title}}</td>
                    <td class="text-one-line">{{result.job_listing.company.name}}</td>
                    <td v-if="result.custom_cover_letter"
                        class="text-one-line">{{result.custom_cover_letter}}
                    </td>
                    <td v-else class="text-one-line">
                      <span class="text-muted font-italic">No cover letter</span>
                    </td>
                    <td class="text-one-line">{{result.status_name}}</td>
                    <td class="text-one-line">
                      <a :href="result.permalink" class="btn btn-action">View</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <loading-icon v-else />
  </div>
</template>

<script>
export default {
  data()
  {
    return {
      query: '',
      applications: [],

      loaded: false,
    };
  },
  computed: {
    queryResults()
    {
      // Don't bother with scoring anything if the query is empty.
      if ( !this.query ) return this.applications;

      // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
      const preparedQuery = fuzzaldrin.prepareQuery( this.query );

      // We use this to keep track of the similarity for each option.
      const scores = {};

      return this
        .applications
        // Score each option & create a new array out of them.
        .map( ( application ) =>
        {
          // See how well each field compares to the query.
          const fieldScores = [
            application.job_listing.company.name,
            application.job_listing.title,
            application.custom_cover_letter,
          ].map( field => fuzzaldrin.score( field, this.query, { preparedQuery } ) );

          scores[ application.id ] = Math.max( ...fieldScores );
          return application;
        } )
        // Remove anything with a really low score.
        .filter( application => scores[ application.id ] > 1 )
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
      const applications = await axios.post( route( 'job-listing.application.index' ) );
      if ( applications.data.success )
        this.applications = applications.data.models;
    },
  },

};
</script>
