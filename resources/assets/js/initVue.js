import Vue from 'vue';
import Echo from 'laravel-echo';

import Vuex from 'vuex';
import AsyncComputed from 'vue-async-computed'
import VueCurrencyFilter from 'vue-currency-filter';
import storeOptions from './store/store';
import LoadingIcon from './components/LoadingIcon';
import Pagination from './components/Pagination';
import VerifiedBadge from './components/VerifiedBadge';

// fixes errors with using lodash in Vue SFCs
Vue.prototype._ = _;

Vue.use(Vuex);
Vue.use(AsyncComputed);
Vue.use(VueCurrencyFilter, {
    symbol: '£',
    thousandsSeparator: ',',
    fractionCount: 2,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: false
});

window.store = new Vuex.Store(storeOptions);

Vue.component('loading-icon', LoadingIcon);
Vue.component('pagination', Pagination);
Vue.component('verified-badge', VerifiedBadge);

let requiresEcho = false;

if (document.getElementById('vue-private-messages')) {
    import('./componentInitializers/PrivateMessages' /* webpackChunkName: "js/components/private-messages" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

if (document.getElementById('vue-job-listings-table')) {
    import('./componentInitializers/JobListingsTable' /* webpackChunkName: "js/components/job-listings-table" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-company-view-applications-table')) {
    import('./componentInitializers/CompanyViewApplicationsTable' /* webpackChunkName: "js/components/company-view-applications-table" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-select2')) {
    import('./componentInitializers/Select2' /* webpackChunkName: "js/components/select2" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-search')) {
    import('./componentInitializers/Search' /* webpackChunkName: "js/components/search" */)
        .then(component => {
            component.default();
        });
}

if (document.getElementById('vue-employee-dashboard')) {
    import('./componentInitializers/EmployeeDashboard' /* webpackChunkName: "js/components/employee-dashboard" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

if (requiresEcho) {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
}
