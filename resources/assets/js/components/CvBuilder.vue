<template>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        data() {
            return {};
        },
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
        mounted() {
            this.$nextTick(() => {});
        },
        methods: {}
    };
</script>
<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>