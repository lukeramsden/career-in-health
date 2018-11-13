import Vue               from 'vue';
import EmployeeDashboard from '../components/EmployeeDashboard.vue';

export default function ()
{
  new Vue( {
    el: '#vue-search',
    store,
    components: {
      EmployeeDashboard,
    },
  } );
}
