import Vue               from 'vue';
import EmployeeDashboard from '../components/EmployeeDashboard.vue';

export default function ()
{
  new Vue( {
    el: '#vue-employee-dashboard',
    store,
    components: {
      EmployeeDashboard,
    },
  } );
}
