<template>
  <div class="container-fluid mt-4">
    <div v-if="loaded" id="listing-show-row" class="row">
      <div class="col-12">
        <div class="card card-custom card-listing">
          <div class="card-body">
            <a :href="route('company.show', {company:company.id})" class="card-subtitle">
              {{company.name}}
              <verified-badge :company="company" />
            </a>
            <h4 class="card-title">{{listing.job_role.name}}</h4>
            <h5>{{ listing.title }}</h5>
            <h6>{{ listing.setting_name }}</h6>
            <div id="small-details">
              <div>
                <p><span
                  class="badge badge-primary badge-pill p-2 px-3">{{ listing.type_name }}</span>
                </p>
              </div>
              <div>
                <p><span class="oi oi-map-marker mr-3"></span>{{ listing.address.location.name }}
                </p>
              </div>
              <div>
                <p>
                  {{listing.min_salary | currency}} -
                  {{listing.max_salary | currency}}
                </p>
              </div>
              <div>
                <p><span class="oi oi-calendar"></span> <span
                  class="text-muted">Last Updated</span> {{ listing.last_edited | dateDiff }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="row">
          <div class="col-12 col-lg-4 order-lg-last">
            <div id="application-filter-card"
                 class="card card-custom-material card-custom-material-hover">
              <div class="card-body p-0">
                <input id="input-query" v-model="query" class="input input-material w-100 p-3"
                       placeholder="Search" type="text">
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8">
            <table class="table w-100">
              <thead class="thead-primary text-light">
                <tr>
                  <th scope="col">Employee Name</th>
                  <th scope="col">Cover Letter</th>
                  <th scope="col">Status</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="result of queryResults" :key="result.id">
                  <td>{{result.employee.full_name}}</td>
                  <td v-if="result.custom_cover_letter">{{result.custom_cover_letter}}</td>
                  <td v-else><span class="text-muted font-italic">No cover letter</span></td>
                  <td>{{result.status_name}}</td>
                  <td><a :href="result.permalink" class="btn btn-action">View</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <loading-icon v-else />
  </div>
</template>

<script>
export default {
  props: {
    listingId: {
      type: Number,
      required: true,
    },
  },
  data()
  {
    return {
      query: '',
      listing: {},
      company: {},
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
      const scores        = {};

      return this
        .applications
        // Score each option & create a new array out of them.
        .map( ( application ) =>
        {
          // See how well each field compares to the query.
          const fieldScores = [
            application.employee.full_name,
            application.custom_cover_letter,
            application.status_name,
            // Creating an array of fields and mapping is easier than writing
            // fz.score(...) six times. Same idea.
            // Scores are a non-normalized number
            // representing how similar the query is to the field.
          ].map( field => fuzzaldrin.score( field, this.query, { preparedQuery } ) );

          // Store the highest score for this option
          // so we can compare it to other options.
          scores[ application.id ] = Math.max( ...fieldScores );

          return application;
        } )
        // Remove anything with a really low score.
        // You might want to play around with this.
        .filter( application => scores[ application.id ] > 1 )
        // Finally, sort by the highest score.
        .sort( ( a, b ) => scores[ b.id ] - scores[ a.id ] );
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

        } );
      } );
  },
  methods: {
    async load()
    {
      console.log( 'loading' );

      const promises = [
        axios.post( route( 'job-listing.get', { jobListing: this.listingId } ) ),
        axios.post( route( 'job-listing.view-applications.get', { jobListing: this.listingId } ) ),
      ];

      const [ listing, applications ] = await Promise.all( promises );

      this.listing      = listing.data.model;
      this.applications = applications.data.models;

      const company = await axios.post( route( 'company.show', {
        company: this.listing.company_id,
      } ) );

      this.company = company.data.model;
    },
  },
};
</script>
