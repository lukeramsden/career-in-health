<template>
    <select>
        <slot></slot>
    </select>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        props: ['options', 'value'],
        data() {
            return {};
        },
        mounted() {
            this.$nextTick(() => {
                $(this.$el)
                // init select2
                    .select2({
                        dropdownAutoWidth: true,
                        width: 'auto'
                    })
                    .val(this.value)
                    .trigger('change')
                    // emit event on change.
                    .change((event) => {
                        vm.$emit('input', event.target.value)
                    })
            });
        },
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        },
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
        watch: {
            value: function (value) {
                // update value
                $(this.$el)
                    .val(value)
                    .trigger('change')
            },
            options: function (options) {
                // update options
                $(this.$el).empty().select2({data: options})
            }
        },

    };
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';

    .select2 {
        display: block;
    }

    .select2-container--default .select2-selection--single {
        border-color: #ced4da;
    }
</style>