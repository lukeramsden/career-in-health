if (document.getElementById('vue-small-private-messages')) {
    import('./initSmallPrivateMessagesComponent' /* webpackChunkName: "js/small-private-messages-component" */)
        .then(component => {
            component.default();
        });
}