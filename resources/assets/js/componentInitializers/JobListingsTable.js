import Vue              from 'vue';
import JobListingsTable from '../components/JobListingsTable';

export default function ()
{
  new Vue( {
    el: '#vue-job-listings-table',
    store,
    components: {
      JobListingsTable,
    },
  } );
}
