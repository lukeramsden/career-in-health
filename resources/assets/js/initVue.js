import Vue  from 'vue';
import Echo from 'laravel-echo';

import Vuex              from 'vuex';
import AsyncComputed     from 'vue-async-computed';
import VueCurrencyFilter from 'vue-currency-filter';
import VueChatScroll     from 'vue-chat-scroll';
import VueSweetAlert     from 'vue-sweetalert2';

import storeOptions  from './store/store';
import LoadingIcon   from './components/LoadingIcon.vue';
import Pagination    from './components/Pagination.vue';
import VerifiedBadge from './components/VerifiedBadge.vue';

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

Vue.filter( 'dateDiff', ( val ) =>
{
  if ( !val ) return '';
  return moment.utc( val ).local().fromNow();
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
Vue.use( VueSweetAlert );

Vue.component( 'loading-icon', LoadingIcon );
Vue.component( 'pagination', Pagination );
Vue.component( 'verified-badge', VerifiedBadge );

/* eslint-disable max-len */
// lazy load components
Vue.component( 'addresses-mini', () => import( /* webpackChunkName: "js/components/addresses-mini" */ './components/AddressesMini' ) );
Vue.component( 'company-dashboard', () => import( /* webpackChunkName: "js/components/company-dashboard" */ './components/CompanyDashboard' ) );
Vue.component( 'company-view-applications-table', () => import( /* webpackChunkName: "js/components/company-view-applications-table" */ './components/CompanyViewApplicationsTable' ) );
Vue.component( 'create-address', () => import( /* webpackChunkName: "js/components/create-address" */ './components/CreateAddress' ) );
Vue.component( 'create-job-listing', () => import( /* webpackChunkName: "js/components/create-job-listing" */ './components/CreateJobListing' ) );
Vue.component( 'employee-dashboard', () => import( /* webpackChunkName: "js/components/employee-dashboard" */ './components/EmployeeDashboard' ) );
Vue.component( 'employee-view-applications-table', () => import( /* webpackChunkName: "js/components/employee-view-applications-table" */ './components/EmployeeViewApplicationsTable' ) );
Vue.component( 'job-listings-table', () => import( /* webpackChunkName: "js/components/job-listings-table" */ './components/JobListingsTable' ) );
Vue.component( 'notifications-index', () => import( /* webpackChunkName: "js/components/notifications-index" */ './components/NotificationsIndex' ) );
Vue.component( 'private-messages', () => import( /* webpackChunkName: "js/components/private-messages" */ './components/PrivateMessages' ) );
Vue.component( 'saved-job-listings-table', () => import( /* webpackChunkName: "js/components/saved-job-listings-table" */ './components/SavedJobListingsTable' ) );
Vue.component( 'search', () => import( /* webpackChunkName: "js/components/search" */ './components/Search' ) );
Vue.component( 'select2', () => import( /* webpackChunkName: "js/components/select2" */ './components/Select2' ) );
Vue.component( 'show-applications-for-job-listing', () => import( /* webpackChunkName: "js/components/show-applications-for-job-listing" */ './components/ShowApplicationsForJobListing' ) );
/* eslint-enable max-len */

window.store = new Vuex.Store( storeOptions );

window.Echo = new Echo( {
  broadcaster: 'socket.io',
  host: `${window.location.hostname}:6001`,
} );

window.VApp = new Vue( {
  el: '#app',
  store,
  mounted()
  {
    this.load().then( () =>
    {
      if ( window.isAuthenticated )
      {
        Echo
          .private( `App.User.${currentUser.id}` )
          .notification( ( n ) => this.store.commit( 'pushNotification', n ) );
      }
    } );
  },
  methods: {
    async load()
    {
      if ( window.isAuthenticated )
        this.store.commit( 'replaceNotifications',
          ( await axios.post( route( 'notifications.index' ) ) ).data.models );
    },
  },
} );
