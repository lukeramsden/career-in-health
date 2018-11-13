import Vue     from 'vue';
import Select2 from '../components/Select2.vue';

export default function ()
{
  new Vue( {
    el: '#vue-select2',
    store,
    components: {
      Select2,
    },
  } );
}
