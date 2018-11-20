import Vue              from 'vue';
import CompanyDashboard from '../components/CompanyDashboard.vue';

export default function ()
{
  new Vue( {
    el: '#vue-company-dashboard',
    store,
    components: {
      CompanyDashboard,
    },
  } );
}
