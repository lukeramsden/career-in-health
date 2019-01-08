import PrivateMessagesModule              from './plugins/PrivateMessages';
import JobListingsTableModule             from './plugins/JobListingsTable';
import CompanyViewApplicationsTableModule from './plugins/CompanyViewApplicationsTable';
import SearchModule                       from './plugins/Search';
import AddressesMiniModule                from './plugins/AddressesMini';

import EmployeeDashboardModule from './plugins/EmployeeDashboard';
import CompanyDashboardModule  from './plugins/CompanyDashboard';

function updateNotificationsBadge()
{
  const { unreadNotificationsCount } = window.store.getters;

  const element     = document.getElementById( 'navbar-notification-unread-badge' );
  element.innerHTML = unreadNotificationsCount;
  element.classList.toggle('badge-danger', unreadNotificationsCount > 0);
  element.classList.toggle('badge-secondary', unreadNotificationsCount <= 0);
}

export default {
  strict: process.env.NODE_ENV !== 'production',
  plugins: [],
  modules: {
    PrivateMessagesModule,
    JobListingsTableModule,
    CompanyViewApplicationsTableModule,
    SearchModule,
    AddressesMiniModule,

    EmployeeDashboardModule,
    CompanyDashboardModule,
  },
  state: {
    userType: '',
    notifications: [],
  },
  mutations: {
    updateUserType:
      ( state, payload ) => state.userType = payload,
    replaceNotifications:
      ( state, payload ) => ( state.notifications = payload, updateNotificationsBadge() ),
    pushNotification:
      ( state, payload ) => ( state.notifications = _.isArray( payload )
        ? [ ...state.notifications, ...payload ]
        : [ ...state.notifications, payload ], updateNotificationsBadge() ),
    notificationsMarkAllAsRead:
      ( state ) => ( state.notifications = state.notifications.map(
        o => ( { ...o, read_at: moment().format( 'YYYY-MM-DD H:m:s' ) } ),
      ) ),
  },
  getters: {
    notifications: ( { notifications } ) =>
      _.clone( notifications || [] ).sort( ( a, b ) =>
      {
        if ( moment( a.created_at ).isAfter( moment( b.created_at ) ) )
          return -1;
        if ( moment( a.created_at ).isBefore( moment( b.created_at ) ) )
          return 1;
        return 0;
      } ),
    notificationsCount: ( { notifications } ) =>
      notifications.length,
    unreadNotifications: ( state, { notifications } ) =>
      _.filter( notifications, {
        read_at: null,
      } ),
    unreadNotificationsCount: ( state, { unreadNotifications } ) =>
      unreadNotifications.length,
  },
};
