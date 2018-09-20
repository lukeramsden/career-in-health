<template>
    <div class="card card-custom mb-3">
        <div class="card-body">
            <div class="row align-content-center">
                <div class="col-12">
                    <h4 class="d-inline-block">{{ schema.label }}</h4>
                    <p v-if="schema.sublabel" class="d-inline-block text-muted" style="font-size: 14px;">{{
                        schema.sublabel }}</p>
                </div>
                <div class="col-12">
                    <cv-item :multiple="false" :model="model" :schema="schema"></cv-item>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        props: ['schema', 'model'],
        mounted() {
            this.$nextTick(() => {
            });
        },
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
    };
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>