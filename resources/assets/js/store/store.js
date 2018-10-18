import PrivateMessagesModule from './plugins/PrivateMessages';
import JobListingsTableModule from './plugins/JobListingsTable';
import CompanyViewApplicationsTableModule from './plugins/CompanyViewApplicationsTable';
import SearchModule from './plugins/Search';

export default {
    strict: process.env.NODE_ENV !== 'production',
    plugins: [],
    modules: {
        PrivateMessagesModule,
        JobListingsTableModule,
        CompanyViewApplicationsTableModule,
        SearchModule,
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