<template>
    <div class="card card-custom mb-3">
        <div class="card-body">
            <div class="row align-content-center">
                <div class="col-12">
                    <h4 class="d-inline-block">{{ schema.label }}</h4>
                    <p v-if="schema.sublabel" class="d-inline-block text-muted" style="font-size: 14px;">
                        {{ schema.sublabel }}
                    </p>
                    <button @click="add" class="btn btn-link float-right"><span class="oi oi-plus"></span></button>
                </div>
                <div class="col-12">
                    <template v-for="(model, index) in model">
                        <cv-item :multiple="true" :model="model" :schema="schema" :index="index"
                                 v-on:delete-cv-item="del"></cv-item>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    export default {
        props: ['schema', 'model'],
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
        methods: {
            del(i) {
                if (!confirm('Are you sure?'))
                    return;

                let modelId = _.get(this, ['model', i, 'id']);

                // deleting real
                if (!!modelId) {
                    axios.delete(_.get(this, 'schema.url') + '/' + modelId)
                        .then((response) => {
                            if (response.data.success)
                                this.model.splice(i, 1);
                        })
                        .catch((error) => {
                            console.log(error);
                            _.forIn(
                                error.response.data.errors,
                                (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                            );
                        });
                } else this.model.splice(i, 1); // deleting create form
            },
            add: _.debounce(() => {
                    this.model.push({editing: true, new: true})
                },
                500,
                {leading: true, trailing: false}
            ),
        },
    };
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>
