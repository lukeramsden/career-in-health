<template>
    <select :name="name" :placeholder="placeholder" :disabled="disabled">
        <slot></slot>
    </select>
</template>

<script>
    import 'select2';
    import 'select2/dist/css/select2.min.css'

    export default {
        props: {
            'name': null,
            'data': null,
            'value': null,
            'placeholder': String,
            'disabled': Boolean,
            'allowClear': Boolean,
        },
        mounted() {
            const self = this;
            $(this.$el)
                .select2({ // init select2
                    dropdownAutoWidth: true,
                    width: 'auto',
                    placeholder: self.placeholder,
                    allowClear: self.allowClear || false,
                })
                .val(this.value)
                .trigger('change')
                // emit event on change.
                .on('change', function () {
                    self.$emit('input', this.value);
                });
        },
        watch: {
            value(value) {
                // update value
                $(this.$el)
                    .val(value)
                    .trigger('change');
            },
            data(data) {
                // update data
                $(this.$el).empty().select2({data});
            },
        },
        destroyed() {
            $(this.$el).off().select2('destroy');
        },
    };
</script>

<style lang="scss">
    .select2 {
        display: block;
    }

    .select2-container--default .select2-selection--single {
        border-color: #ced4da;
    }
</style>