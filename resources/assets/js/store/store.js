import PrivateMessagesModule from './plugins/PrivateMessages';
import JobListingsTableModule from './plugins/JobListingsTable';
import CompanyViewApplicationsTableModule from './plugins/CompanyViewApplicationsTable';

export default {
    strict: process.env.NODE_ENV !== 'production',
    plugins: [],
    modules: {
        PrivateMessagesModule,
        JobListingsTableModule,
        CompanyViewApplicationsTableModule,
    },
    state: {
        userType: '',
    },
    mutations: {
        updateUserType:
            (state, payload) => state.userType = payload,
    },
    getters: {},
};