import Vue from 'vue';
import PrivateMessages from './components/PrivateMessages';
import VueChatScroll from 'vue-chat-scroll';
Vue.use(VueChatScroll);

export default function () {
    new Vue({
        el: '#vue-private-messages',

        components: {
            PrivateMessages,
        },
    });
}