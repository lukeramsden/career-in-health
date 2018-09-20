<template>
    <div class="row">
        <div class="col-sm-12 col-md-8 col-xl-9">
            <template v-for="(schema, index) in schemas">
                <cv-section-multiple v-if="schema.multiple" :schema="schema"
                                     :model="model[schema.name]"></cv-section-multiple>
                <cv-section-single v-else :schema="schema" :model="model[schema.name]"></cv-section-single>
            </template>
        </div>
        <div class="col-sm-12 col-md-4 col-xl-3 order-first order-md-last mb-4">
            <div class="btn-group-vertical">
                <a  v-if="isOnboarding"
                    :href="route('employee.next-onboard-step')"
                    class="btn btn-action disabled" id="next-step">Next Step</a>
                <button class="btn btn-primary" @click="show">View CV</button>
                <a :href="route('cv.pdf.download')" target="_blank" class="btn btn-primary">Download CV</a>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    import CvSectionMultiple from './CvSectionMultiple';
    import CvSectionSingle from './CvSectionSingle';
    import LoadingIcon from './LoadingIcon';
    import DatePicker from './DatePicker';
    import Select2 from './Select2';
    import FileUpload from './FileUpload';

    export default {
        components: [
            CvSectionMultiple,
            CvSectionSingle,
        ],
        mounted() {
            this.$nextTick(() => {
            });
        },
        computed: {
            ...mapState({
                isOnboarding: 'isOnboarding',
            }),
            ...mapGetters({}),
        },
    };
</script>
<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>