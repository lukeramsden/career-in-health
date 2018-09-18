import Vue from 'vue';
import PrivateMessages from './components/PrivateMessages';

export default function () {
    new Vue({
        el: '#vue-private-messages',

        components: {
            PrivateMessages,
        },
    });
}