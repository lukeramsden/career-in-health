<template>
    <div class="cv-item">
        <template v-if="model.editing">
            <form @submit.prevent="save">
                <div v-for="(field, index) in schema.fields" :key="fieldId(field)">
                    <template v-if="!_.isFunction(_.get(field, 'if')) || field.if(field, model)">
                        <template v-if="_.get(field, 'type') === 'input'">
                            <div class="form-group">
                                <template v-if="_.get(field, 'label')">
                                    <label
                                        :for="fieldId(field)">
                                        {{ field.label }}
                                    </label>
                                </template>
                                <template v-if="_.get(field, 'inputType') === 'text'">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="model[field.model]"
                                        :id="fieldId(field)"
                                        :name="field.model"
                                        :maxlength="field.max"
                                        :aria-describedby="fieldId(field) + 'HelpBlock'"
                                        :required="field.required">

                                    <template v-if="_.get(field, 'helpText')">
                                        <small
                                            class="form-text text-muted"
                                            :id="fieldId(field) + 'HelpBlock'">
                                            {{ field.helpText }}
                                        </small>
                                    </template>
                                </template>
                                <template v-else-if="_.get(field, 'inputType') === 'area'">
                                        <textarea
                                            class="form-control"
                                            cols="30"
                                            rows="10"
                                            v-model="model[field.model]"
                                            :id="fieldId(field)"
                                            :name="field.model"
                                            :maxlength="field.max"
                                            :placeholder="_.get(field, 'helpText')"
                                            :required="field.required">{{ model[field.model] }}</textarea>
                                </template>
                            </div>
                        </template>
                        <template v-else-if="_.get(field, 'type') === 'datePicker'">
                            <template v-if="_.get(field, 'multiple')">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col" v-for="(field, index) in field.models" :key="fieldId(field)">
                                            <date-picker
                                                :id="fieldId(field)"
                                                :schema="field"
                                                @update-date="updateDate"
                                                v-once></date-picker>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <date-picker
                                    :id="fieldId(field)"
                                    :schema="field"
                                    @update-date="updateDate"
                                    v-once></date-picker>
                            </template>
                        </template>
                        <template v-else-if="_.get(field, 'type') === 'checkbox'">
                            <div class="form-group">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        :id="fieldId(field)"
                                        :name="field.model"
                                        v-model="model[field.model]">
                                    <label
                                        class="form-check-label"
                                        v-if="_.get(field, 'label')"
                                        :for="fieldId(field)">
                                        {{ field.label }}
                                    </label>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="_.get(field, 'type') === 'dropdown'">
                            <div class="form-group">
                                <label v-if="field.label" :for="fieldId(field)">{{ field.label }}</label>
                                <template v-if="_.get(field, 'handler') === 'select2'">
                                    <select2
                                        :id="fieldId(field)"
                                        v-model="model[field.model]">
                                        <option :value="null">-</option>
                                        <option v-for="item in field.data" :value="item.value">{{ item.name }}</option>
                                    </select2>
                                </template>
                                <template v-else>
                                    <select class="custom-select" :id="fieldId(field)" v-model="model[field.model]">
                                        <option :value="null">-</option>
                                        <option v-for="item in field.data" :value="item.value">{{ item.name }}</option>
                                    </select>
                                </template>
                            </div>
                        </template>
                        <template v-else-if="_.get(field, 'type') === 'file'">
                            <div class="form-group">
                                <file-upload
                                    :id="fieldId(field)"
                                    :label="field.label"
                                    :helpText="field.helpText"
                                    :required="field.required"
                                    :max="field.max"
                                    :accept="field.fileTypes"
                                    v-model="model[field.model]"/>
                            </div>
                        </template>
                    </template>
                </div>

                <button :type="loading ? 'button' : 'submit'" class="btn btn-action w-25">
                    <loading-icon v-if="loading"/>
                    <template v-else>Save</template>
                </button>
                <button type="button" class="btn btn-link" @click="cancel">Cancel</button>
            </form>
        </template>
        <template v-else>
            <div class="cv-item-inner">
                <!-- PREFERENCES -->
                <template v-if="schema.name === 'preferences'">
                    <template v-if="model.job_role">
                        <small><b>Job Role</b></small>
                        <p class="my-1">{{ schema.fields[0].data[model.job_role-1].name }}</p>
                    </template>
                    <template v-if="model.setting">
                        <small><b>Job Setting</b></small>
                        <p class="my-1">{{ schema.fields[1].data[model.setting-1].name }}</p>
                    </template>
                    <template v-if="model.type">
                        <small><b>Job Type</b></small>
                        <p class="my-1">{{ schema.fields[2].data[model.type-1].name }}</p>
                    </template>
                    <small><b>Relocation</b></small>
                    <p class="my-1">
                        <template v-if="model.willing_to_relocate">Willing to relocate</template>
                        <template v-else>Not willing to relocate</template>
                    </p>
                </template>
                <!-- EDUCATION -->
                <template v-if="schema.name === 'education'">
                    <p class="my-1">{{ model.degree }} in {{ model.field_of_study }}</p>
                    <p class="my-1">{{ model.school_name }} - {{ model.location }}</p>
                    <template v-if="validDate(model.end_date)">
                        <p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date,
                            'MMMM YYYY') }}</p>
                    </template>
                    <template v-else>
                        <p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>
                    </template>
                </template>
                <!-- WORK EXPERIENCE -->
                <template v-if="schema.name === 'work_experience'">
                    <p class="my-1">{{ model.job_title }} at {{ model.company_name }}</p>
                    <p class="my-1">{{ model.location }}</p>
                    <template v-if="validDate(model.end_date)">
                        <p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date,
                            'MMMM YYYY') }}</p>
                    </template>
                    <template v-else>
                        <p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>
                    </template>
                    <p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>
                </template>
                <!-- CERTIFICATIONS / LICENSES -->
                <template v-if="schema.name === 'certifications'">
                    <p class="my-1">{{ model.title }}</p>
                    <template v-if="validDate(model.end_date)">
                        <p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date,
                            'MMMM YYYY') }}</p>
                    </template>
                    <template v-else>
                        <p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>
                    </template>
                    <p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>
                    <a :href="model.url" class="btn btn-link">View File</a>
                </template>
            </div>
            <button v-if="multiple" class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)">
                <span class="oi oi-delete"></span></button>
            <button class="btn btn-link btn-sm float-right" @click="edit"><span class="oi oi-pencil"></span></button>
        </template>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex'

    import LoadingIcon from './LoadingIcon';
    import DatePicker from './DatePicker';
    import Select2 from './Select2';
    import FileUpload from './FileUpload';

    export default {
        components: {
            LoadingIcon,
            DatePicker,
            Select2,
            FileUpload
        },
        props: ['schema', 'model', 'index', 'multiple'],
        data() {
            return {
                loading: false,
            }
        },
        computed: {
            ...mapState({}),
            ...mapGetters({}),
        },
        methods: {
            //
            save() {
                const self = this;
                self.loading = true;

                if (!_.get(this, 'schema.url', false)) {
                    setTimeout(() => {
                        self.$set(self.model, 'editing', false);
                        self.loading = false;
                    }, 500);
                    return;
                }

                // construct schema
                const fields = _
                // start chain
                    .chain(this.schema.fields)
                    // get array of all "models" fields
                    .flatMap('models')
                    // add original to prev array
                    .concat(this.schema.fields)
                    .thru((fields) => {
                        return _.map(fields, (field) => {
                            if (!field || !_.isFunction(field.if))
                                return field;

                            if (field.if(field, self.model))
                                return field;
                        });
                    });

                const models = fields
                // get all "model" fields
                    .flatMap('model')
                    // remove empty vals
                    .compact()
                    // end
                    .value();

                const inputTypes = fields
                // get all "model" fields
                    .flatMap('type')
                    // remove empty vals
                    .compact()
                    // remove duplicate values
                    .uniq()
                    // end
                    .value();

                const multiple = _.get(this, 'multiple');
                const hasFileInput = inputTypes.includes('file');

                let options = {};

                if (hasFileInput) {
                    const files = fields
                        .flatMap((el) => _.get(el, 'type') === 'file' ? [el] : [])
                        .compact()
                        .value();

                    _.set(options, ['headers', 'Content-Type'], 'multipart/form-data');
                    options.data = new FormData();

                    for (file in files)
                        options.data.append(file.model, this.model[file.model]);

                    /**
                     * Iterates all models, and appends them to options.data
                     * The catch is that value is run through JSON.stringify if it is an array
                     */
                    // iterate models
                    _.forIn(
                        // only get model keys that are in schema
                        _.pick(this.model, models),
                        // run "func" with "main args" after running "main args" through "transform"
                        // key is left intact, v is : stringified if array, else left intact
                        (v, k) => _.overArgs(
                            // func
                            (k, v) => options.data.append(k, v),
                            // transform
                            [
                                k => k, // stays the same
                                v => _.isArray(v) ? JSON.stringify(v) : v, // JSON.stringify all arrays
                            ])(k, v) // main args
                    );
                } else {
                    // map internal model to schema
                    options.data = _.pick(this.model, models);
                }

                if (multiple) {
                    // get model id
                    let modelId = _.get(this, 'model.id', null);

                    options.url = !!modelId ?
                        _.get(this, 'schema.url') + '/' + modelId // multiple & updating existing
                        : _.get(this, 'schema.url');                // multiple & creating new

                    options.method = !!modelId ?
                        'put'  // multiple & updating existing
                        : 'post' // multiple & creating new

                } else {
                    options.method = 'put';
                    options.url = _.get(this, 'schema.url');
                }

                axios(options)
                    .then((response) => {
                        if (response.data.success) {
                            self.$set(self.model, 'editing', false);

                            self.$set(self.model, 'id', _.get(response, 'data.model.id'));
                        }

                        self.loading = false;
                        self.model.new = false;
                        $('#next-step').removeClass('disabled');
                    })
                    .catch((error) => {
                        console.log(error);
                        _.forIn(
                            error.response.data.errors,
                            (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                        );
                        self.loading = false;
                    });
            },
            //
            cancel() {
                if (_.get(this, 'multiple')) {
                    // all model keys from schema
                    let fields = _.map(this.schema.fields, 'model');
                    // all fields that are required
                    let requiredFields = _.filter(this.schema.fields, ['required', true]);
                    // get model with only fields that are in schema
                    // this mean we can store metadata for the form in the model
                    let model = _.pick(this.model, fields);

                    // if less keys in model than required fields
                    // delete item
                    if (_.keys(model).length < requiredFields.length)
                        this.$emit('delete-cv-item', this.index);
                }

                // invert editing
                this.loading = false;
                this.$set(this.model, 'editing', false);
            },
            //
            edit() {
                this.$set(this.model, 'editing', true)
            },
            // turn field model in to pretty Id
            fieldId: field => _.camelCase('input_' + field.model),
            // event ($emit) handler for datepicker value changing
            updateDate(date, model) {
                this.$set(this.model,
                    model,
                    !date ? undefined : moment(date || '').format('Y-MM-DD'));
            },
            // pretty-print date with momentjs
            formatDate: (date, format) => moment(date || '').format(format),
            validDate: (date) => moment(date || '')._isValid,
        },
    };
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
</style>
