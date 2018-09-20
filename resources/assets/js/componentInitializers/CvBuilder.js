import Vue from 'vue';
import CvBuilder from '../components/CvBuilder/CvBuilder';

export default function () {
    new Vue({
        el: '#vue-cv-builder',
        store,
        components: {
            CvBuilder,
        },
    });
}