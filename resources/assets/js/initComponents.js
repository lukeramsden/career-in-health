let requiresEcho = false;

if (document.getElementById('vue-private-messages')) {
    import('./initPrivateMessagesComponent' /* webpackChunkName: "js/private-messages-component" */)
        .then(component => {
            component.default();
        });

    requiresEcho = true;
}

import Echo from 'laravel-echo';

if (requiresEcho) {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
}