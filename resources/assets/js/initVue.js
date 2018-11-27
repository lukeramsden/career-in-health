/* eslint-disable max-len */
import Vue  from 'vue';
import Echo from 'laravel-echo';

import Vuex              from 'vuex';
import AsyncComputed     from 'vue-async-computed';
import VueCurrencyFilter from 'vue-currency-filter';
import VueChatScroll     from 'vue-chat-scroll';
import VueSweetAlert     from 'vue-sweetalert2';

import storeOptions      from './store/store';
import LoadingIcon       from './components/LoadingIcon.vue';
import Pagination        from './components/Pagination.vue';
import VerifiedBadge     from './components/VerifiedBadge.vue';

// fixes errors with using lodash in Vue SFCs
Vue.prototype._ = _;

Vue.mixin( {
  methods: {
    route( ...args )
    {
      return window.route( ...args );
    },
  },
} );

Vue.use( Vuex );
Vue.use( AsyncComputed );
Vue.use( VueChatScroll );
Vue.use( VueCurrencyFilter, {
  symbol: '£',
  thousandsSeparator: ',',
  fractionCount: 2,
  fractionSeparator: '.',
  symbolPosition: 'front',
  symbolSpacing: false,
} );
Vue.use(VueSweetAlert);

Vue.component( 'loading-icon', LoadingIcon );
Vue.component( 'pagination', Pagination );
Vue.component( 'verified-badge', VerifiedBadge );

// lazy load components
Vue.component( 'addresses-mini', () => import( /* webpackChunkName: "js/components/addresses-mini" */ './components/AddressesMini' ) );
Vue.component( 'company-dashboard', () => import( /* webpackChunkName: "js/components/company-dashboard" */ './components/CompanyDashboard' ) );
Vue.component( 'company-view-applications-table', () => import( /* webpackChunkName: "js/components/company-view-applications-table" */ './components/CompanyViewApplicationsTable' ) );
Vue.component( 'create-address', () => import( /* webpackChunkName: "js/components/create-address" */ './components/CreateAddress' ) );
Vue.component( 'create-job-listing', () => import( /* webpackChunkName: "js/components/create-job-listing" */ './components/CreateJobListing' ) );
Vue.component( 'employee-dashboard', () => import( /* webpackChunkName: "js/components/employee-dashboard" */ './components/EmployeeDashboard' ) );
Vue.component( 'job-listings-table', () => import( /* webpackChunkName: "js/components/job-listings-table" */ './components/JobListingsTable' ) );
Vue.component( 'private-messages', () => import( /* webpackChunkName: "js/components/private-messages" */ './components/PrivateMessages' ) );
Vue.component( 'search', () => import( /* webpackChunkName: "js/components/search" */ './components/Search' ) );
Vue.component( 'select2', () => import( /* webpackChunkName: "js/components/select2" */ './components/Select2' ) );

window.store = new Vuex.Store( storeOptions );

window.Echo = new Echo( {
  broadcaster: 'socket.io',
  host: `${window.location.hostname}:6001`,
} );

window.VApp = new Vue( {
  el: '#app',
  store,
} );
