import Vue from 'vue';
import SmallPrivateMessages from './components/SmallPrivateMessages';

export default function () {
    new Vue({
        el: '#vue-small-private-messages',

        components: {
            SmallPrivateMessages,
        },
    });
}