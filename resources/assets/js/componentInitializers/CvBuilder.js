import Vue from 'vue';
import CvBuilder from '../components/CvBuilder';

Vue.filter('truncate', (text, length, clamp) => {
    clamp = clamp || '...';
    const node = document.createElement('div');
    node.innerHTML = text;
    const content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
});

export default function () {
    new Vue({
        el: '#vue-cv-builder',
        store,
        components: {
            CvBuilder,
        },
        data: window.data.cvBuilder,
    });
}