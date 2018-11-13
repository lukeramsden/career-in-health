import Vue    from 'vue';
import Search from '../components/Search.vue';

export default function ()
{
  new Vue( {
    el: '#vue-search',
    store,
    components: {
      Search,
    },
  } );
}
