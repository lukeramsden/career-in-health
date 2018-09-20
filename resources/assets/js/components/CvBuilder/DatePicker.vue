<template>
    <div>
        <template v-if="_.get(schema, 'inline')">
            <div></div>
        </template>
        <template v-else>
            <input type="text" class="form-control" :required="_.get(schema, 'required')">
        </template>
        <template v-if="_.get(schema, 'label')">
            <small class="form-text text-muted">{{ schema.label }}</small>
        </template>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        props: ['schema'],
        data() {
            return {};
        },
        mounted() {
            this.$nextTick(() => {
                let name = _.get(this, 'schema.model');

                $(this.$el.children[0])
                    .datepicker(_.get(this, 'schema.options', {}))
                    .datepicker('setDate', moment(this.$parent.model[name] || '').toDate())
                    .on('changeDate', e => {
                        this.$emit('update-date', e.date, name);
                    });
            });
        },
        beforeDestroy: () => $(this.$el).datepicker('hide').datepicker('destroy'),
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