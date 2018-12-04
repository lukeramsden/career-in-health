import PrivateMessagesModule              from './plugins/PrivateMessages';
import JobListingsTableModule             from './plugins/JobListingsTable';
import CompanyViewApplicationsTableModule from './plugins/CompanyViewApplicationsTable';
import SearchModule                       from './plugins/Search';
import AddressesMiniModule                from './plugins/AddressesMini';

import EmployeeDashboardModule from './plugins/EmployeeDashboard';
import CompanyDashboardModule  from './plugins/CompanyDashboard';

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
      ( state, payload ) => state.notifications = payload,
    pushNotification:
      ( state, payload ) => state.notifications = _.isArray( payload )
        ? [ ...state.notifications, ...payload ]
        : [ ...state.notifications, payload ],
  },
  getters: {},
};
