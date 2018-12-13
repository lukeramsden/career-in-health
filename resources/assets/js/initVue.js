/* global currentUser, isAuthenticated, toggleNotificationDrawer */
import Vue  from 'vue';
import Echo from 'laravel-echo';

import Vuex              from 'vuex';
import AsyncComputed     from 'vue-async-computed';
import VueCurrencyFilter from 'vue-currency-filter';
import VueChatScroll     from 'vue-chat-scroll';
import VueSweetAlert     from 'vue-sweetalert2';
import VueMomentsAgo     from 'vue-moments-ago';
import VueNotifications  from 'vue-notification';

import storeOptions  from './store/store';
import LoadingIcon   from './components/LoadingIcon.vue';
import Pagination    from './components/Pagination.vue';
import VerifiedBadge from './components/VerifiedBadge.vue';
import Notification  from './components/Notification.vue';

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
Vue.use( VueNotifications );

Vue.component( 'loading-icon', LoadingIcon );
Vue.component( 'pagination', Pagination );
Vue.component( 'verified-badge', VerifiedBadge );
Vue.component( 'moments-ago', VueMomentsAgo );
Vue.component( 'notification', Notification );

/* eslint-disable max-len */
// lazy load components
Vue.component( 'addresses-mini', ~import( './components/AddressesMini' ) );
Vue.component( 'company-dashboard', ~import( './components/CompanyDashboard' ) );
Vue.component( 'company-view-applications-table', ~import('./components/CompanyViewApplicationsTable' ) );
Vue.component( 'create-address', ~import(  './components/CreateAddress' ) );
Vue.component( 'create-job-listing', ~import(  './components/CreateJobListing' ) );
Vue.component( 'cv-builder', ~import(  './components/CvBuilder' ) );
Vue.component( 'employee-dashboard', ~import(  './components/EmployeeDashboard' ) );
Vue.component( 'employee-view-applications-table', ~import( './components/EmployeeViewApplicationsTable' ) );
Vue.component( 'job-listings-table', ~import(  './components/JobListingsTable' ) );
Vue.component( 'latest-notifications', ~import( './components/LatestNotifications' ) );
Vue.component( 'notifications-index', ~import( './components/NotificationsIndex' ) );
Vue.component( 'private-messages', ~import( './components/PrivateMessages' ) );
Vue.component( 'saved-job-listings-table', ~import( './components/SavedJobListingsTable' ) );
Vue.component( 'search', ~import( './components/Search' ) );
Vue.component( 'select2', ~import( './components/Select2' ) );
Vue.component( 'show-applications-for-job-listing', ~import( './components/ShowApplicationsForJobListing' ) );
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
    console.log('VApp:mounted');
    this
      .load()
      .then( () =>
      {
        console.log('VApp:loaded');

        if ( window.isAuthenticated )
        {
          const pushNotification = ( n ) =>
          {
            console.log( n );
            this.$store.commit( 'pushNotification', n );
            this.$notify({
              group: 'notifications',
              speed: 600,
              duration: 7500,
              data: n,
            });
          };

          window
            .Echo
            .private( `App.User.${currentUser.id}` )
            .notification( pushNotification );
        }
      } );
  },
  methods: {
    async load()
    {
      if ( isAuthenticated )
      {
        const notifications = await axios.post( route( 'notifications.get' ) );
        this.$store.commit( 'pushNotification', notifications.data.models );
      }
    },
  },
} );
