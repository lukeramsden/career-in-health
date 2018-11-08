import Vue                          from 'vue';
import CompanyViewApplicationsTable from '../components/CompanyViewApplicationsTable.vue';

export default function ()
{
  new Vue( {
    el: '#vue-company-view-applications-table',
    store,
    components: {
      CompanyViewApplicationsTable,
    },
  } );
}
