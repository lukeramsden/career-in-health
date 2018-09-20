import Vue from 'vue';
import Vuex from 'vuex';

import storeOptions from './store/store';

Vue.use(Vuex);
window.store = new Vuex.Store(storeOptions);

let requiresEcho = false;

if (document.getElementById('vue-private-messages')) {
    import('./componentInitializers/PrivateMessages' /* webpackChunkName: "js/private-messages-component" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

if (document.getElementById('vue-cv-builder')) {
    import('./componentInitializers/CvBuilder' /* webpackChunkName: "js/cv-builder" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

/**
 * Initialize Laravel Echo
 */

import Echo from 'laravel-echo';

if (requiresEcho) {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
}