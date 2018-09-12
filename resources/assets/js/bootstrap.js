
try {
    window.Popper     = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');

    window._          = require('lodash');
    window.lity       = require('lity');
    window.mime       = require('mime');
    window.filesize   = require('file-size');
    window.changeCase = require('change-case');
    window.fuzzaldrin = require('fuzzaldrin-plus');

    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
} catch (e) {}

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
