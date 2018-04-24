@extends('layouts.app')
@section('content')
    <div class="container">
        <cv-builder :schemas="schemas" :model="model"></cv-builder>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
    
    <style>

    </style>
@endsection
@section('script')
    {{-- VUE TEMPLATES --}}
    
    @verbatim
        <script type="text/x-template" id="template__cv_builder">
            <div class="row">
                <div class="col-sm-7">
                    <template v-for="(schema, index) in schemas">
                        <cv-section :schema="schema" :model="model[schema.name]"></cv-section>
                    </template>
                </div>
                <div class="col-sm-5">
                
                </div>
            </div>
        </script>
        
        <script type="text/x-template" id="template__cv_section">
            <div class="card card-custom mb-3">
                <div class="card-body">
                    <div class="row align-content-center">
                        <div class="col-12">
                            <h4 class="d-inline-block">{{ schema.label }}</h4>
                            <button @click="add" class="btn btn-link float-right"><span class="oi oi-plus"></span></button>
                        </div>
                        <div class="col-12">
                            <template v-for="(model, index) in model">
                                <cv-item :model="model" :schema="schema" :index="index" v-on:delete-cv-item="del"></cv-item>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        
        <script type="text/x-template" id="template__cv_item">
            <div class="cv-item">
                <template v-if="model.editing">
                    <form @submit.prevent="model.editing = !model.editing">
                        <div v-for="(field, index) in schema.fields" :key="fieldId(field)">
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
                        </div>
                    
                        <button type="button" class="btn btn-action w-25" @click="save">Save</button>
                        <button type="button" class="btn btn-link" @click="cancel">Cancel</button>
                    </form>
                </template>
                <template v-else>
                    <div class="cv-item-inner">
                        <!-- EDUCATION -->
                        <template v-if="schema.name === 'education'">
                            <p class="my-1">{{ model.degree }} in {{ model.field_of_study }}</p>
                            <p class="my-1">{{ model.school_name }} - {{ model.location }}</p>
                            <template v-if="_.isDate(model.end_date)">
                                <p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>
                            </template>
                            <template v-else>
                                <p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>
                            </template>
                        </template>
                        <!-- WORK EXPERIENCE -->
                    </div>
                    <button class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)"><span class="oi oi-delete"></span></button>
                    <button class="btn btn-link btn-sm float-right" @click="edit"><span class="oi oi-pencil"></span></button>
                </template>
            </div>
        </script>
        
        <script type="text/x-template" id="template__date-picker">
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
        </script>
    @endverbatim
    
    {{-- development version, includes helpful console warnings --}}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    
    <script>
        Vue.component('cv-builder', {
            template: '#template__cv_builder',
            props: ['schemas', 'model'],
        });
        
        Vue.component('cv-section', {
            template: '#template__cv_section',
            props: ['schema', 'model'],
            methods: {
                del (i) {
                    this.model.splice(i, 1);
                },
                add () {
                    this.model.push({editing: true});
                },
            },
        });
        
        Vue.component('cv-item', {
            template: '#template__cv_item',
            props: ['schema', 'model', 'index'],
            methods: {
                //
                save () {
                    console.log('save')
                },
                //
                cancel () {
                    // invert editing
                    this.model.editing = !this.model.editing;
                    
                    // all model keys from schema
                    let fields = _.map(this.schema.fields, 'model');
                    // all fields that are required
                    let requiredFields = _.filter(this.schema.fields, ['required', true]);
                    // get model with only fields that are in schema
                    // this mean we can store metadata for the form in the model
                    let model = _.pick(this.model, fields);
                    
                    // if less keys in model than required fields
                    // delete item
                    if(_.keys(model).length < requiredFields.length)
                        this.$emit('delete-cv-item', this.index);
                },
                //
                edit () { this.$set(this.model, 'editing', true) },
                // turn field model in to pretty Id
                fieldId: field => _.camelCase('input_' + field.model),
                // event ($emit) handler for datepicker value changing
                updateDate (date, model) { this.$set(this.model, model, date) },
                // pretty-print date with momentjs
                formatDate: (date, format) => moment(date).format(format),
            },
        });
        
        Vue.component('date-picker', {
            template: '#template__date-picker',
            props: ['schema'],
            mounted: function() {
                var self = this;
                let name = _.get(self, 'schema.model');
                $(self.$el.children[0])
                    .datepicker(_.get(self, 'schema.options', {}))
                    .datepicker('setDate', self.$parent.model[name])
                    .on('changeDate', (e) => {
                        self.$emit('update-date', e.date, name);
                    });
            },
            beforeDestroy: () => $(this.$el).datepicker('hide').datepicker('destroy'),
        });
        
        const schemas = [
            {
                name: 'education',
                label: 'Education',
                fields: [
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Degree',
                        model: 'degree',
                        helpText: 'e.g. Diploma, Bachelor\'s, PhD.',
                        required: true,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'College or University',
                        model: 'school_name',
                        required: true,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Field Of Study',
                        model: 'field_of_study',
                        helpText: 'e.g. Management, Nursing, Psychology.',
                        required: true,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'City',
                        model: 'location',
                        helpText: 'e.g. London, Manchester, Birmingham.',
                        required: true,
                    },
                    {
                        type: 'datePicker',
                        multiple: true,
                        models: [
                            {
                                model: 'start_date',
                                label: 'Start Date',
                                required: true,
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                },
                            },
                            {
                                model: 'end_date',
                                label: 'End Date (leave empty if you are still here TODO:placeholder)',
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                    clearBtn: true,
                                },
                            },
                        ],
                    },
                ],
            },
            /*{
                name: 'work_experience',
                label: 'Work Experience',
                fields: [
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Job Title',
                        model: 'job_title',
                        helpText: 'e.g. Manager, Senior Nurse, Midwife.',
                        required: true,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Company Name',
                        model: 'company_name',
                        helpText: 'Name of the company where you worked.',
                        required: true,
                    },
                    {
                        type: 'input',
                        inputType: 'area',
                        max: 500,
                        label: 'Description',
                        model: 'description',
                        helpText: 'A small description of your time there, and your role in the business.',
                        required: false,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'City',
                        model: 'location',
                        helpText: 'e.g. London, Manchester, Birmingham.',
                        required: true,
                    },
                ],
            },*/
        ]
        
        let data = {
            model: {
                education: [
                    {
                        degree: 'PhD',
                        field_of_study: 'Computer Science',
                        school_name: 'MIT',
                        location: 'Boston, MA, USA',
                        start_date: new Date(2014, 9),
                    }
                ],
                work_experience: [],
            },
            schemas: schemas,
        }
        
        Vue.mixin({
            methods: {
                capitalizeFirstLetter: str => str.charAt(0).toUpperCase() + str.slice(1),
            }
        })
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
    </script>
@endsection