import Vue              from 'vue';
import JobListingsTable from '../components/JobListingsTable.vue';

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
