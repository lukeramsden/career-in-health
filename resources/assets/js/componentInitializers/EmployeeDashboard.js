import Vue               from 'vue';
import EmployeeDashboard from '../components/EmployeeDashboard';

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
