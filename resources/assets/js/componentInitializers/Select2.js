import Vue from 'vue';
import Select2 from '../components/Select2';

export default function () {
    new Vue({
        el: '#vue-select2',
        store,
        components: {
            Select2,
        },
    });
}