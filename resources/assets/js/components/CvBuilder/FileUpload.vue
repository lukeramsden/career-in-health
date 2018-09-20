<template>
    <div>
        <label v-if="label">{{ label }}</label>
        <div class="custom-file">
            <input
                :id="id"
                :accept="mimeTypes()"
                :required="required"
                class="custom-file-input"
                type="file"
                ref="file"
                @change="change()"/>

            <label class="custom-file-label" v-if="label" :for="id">
                <template v-if="file">{{ file.name }}</template>
                <template v-else>Choose file...</template>
            </label>
        </div>

        <small v-if="helpText" class="text-muted">{{ helpText }}</small>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        props: ['label', 'id', 'accept', 'helpText', 'required', 'max'],
        mounted() {
            this.$nextTick(() => {
            });
        },
        data() {
            return {
                file: null
            }
        },
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
        methods: {
            change() {
                let file = this.$refs.file.files[0];

                if (filesize(file.size).to('KB') > this.max) {
                    toastr.error('File too big!');
                    return;
                }

                this.$emit('input', file);
                this.file = file;
            },
            mimeTypes() {
                return _.map(this.accept, (el) => {
                    return mime.getType(el);
                });
            },
        },
    };
</script>
<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>