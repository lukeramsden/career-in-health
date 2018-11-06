import Vue from 'vue';
import Echo from 'laravel-echo';

import Vuex from 'vuex';
import AsyncComputed from 'vue-async-computed'
import VueCurrencyFilter from 'vue-currency-filter';

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

import storeOptions from './store/store';

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

if (document.getElementById('vue-search')) {
    import('./componentInitializers/Search' /* webpackChunkName: "js/search-component" */)
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
