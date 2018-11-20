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
  },
  mutations: {
    updateUserType:
      ( state, payload ) => state.userType = payload,
  },
  getters: {},
};
