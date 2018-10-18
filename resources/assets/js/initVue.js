import Vue from 'vue';
import Echo from 'laravel-echo';

import Vuex from 'vuex';
import storeOptions from './store/store';

// fixes errors with using lodash in Vue SFCs
Vue.prototype._ = _;

Vue.use(Vuex);
window.store = new Vuex.Store(storeOptions);

import LoadingIcon from './components/LoadingIcon';
Vue.component('loading-icon', LoadingIcon);

let requiresEcho = false;

if (document.getElementById('vue-private-messages')) {
    import('./componentInitializers/PrivateMessages' /* webpackChunkName: "js/private-messages-component" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

if (document.getElementById('vue-job-listings-table')) {
    import('./componentInitializers/JobListingsTable' /* webpackChunkName: "js/job-listings-table-component" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-company-view-applications-table')) {
    import('./componentInitializers/CompanyViewApplicationsTable' /* webpackChunkName: "js/company-view-applications-table-component" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-select2')) {
    import('./componentInitializers/Select2' /* webpackChunkName: "js/select2-component" */)
        .then(component => {
            component.default();
        });
}

if (requiresEcho) {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
}
