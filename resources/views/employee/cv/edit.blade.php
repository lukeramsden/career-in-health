@extends('layouts.app')
@section('content')
    <div class="container">
        <cv-builder :schemas="schemas" :model="model"></cv-builder>
    </div>
@endsection
@section('stylesheet')
    <style>

    </style>
@endsection
@section('script')
    {{-- VUE TEMPLATES --}}
    
    @verbatim
        <script type="text/x-template" id="template__cv_builder">
            <div>
                <template v-for="(schema, index) in schemas">
                    <cv-section :schema="schema" :model="model[schema.name]"></cv-section>
                </template>
            </div>
        </script>
        
        <script type="text/x-template" id="template__cv_section">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="row align-content-center">
                        <div class="col-12">
                            <h4 class="d-inline-block">{{ schema.label }}</h4>
                            <button @click="model.push({editing:true})" class="btn btn-link float-right"><span class="oi oi-plus"></span></button>
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
                        <div class="form-group">
                            <label for="inputDegree">Degree</label>
                            <input v-model="model.degree" type="text" id="inputDegree" class="form-control" aria-describedby="degreeHelpBlock" required>
                            <small id="degreeHelpBlock" class="form-text text-muted">e.g. Diploma, Bachelor's, PhD.</small>
                        </div>

                        <div class="form-group">
                            <label for="inputSchoolName">College or University</label>
                            <input v-model="model.school_name" type="text" id="inputSchoolName" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputFieldOfStudy">Field Of Study</label>
                            <input v-model="model.field_of_study" type="text" id="inputFieldOfStudy" class="form-control" aria-describedby="fieldOfStudyHelpBlock" required>
                            <small id="fieldOfStudyHelpBlock" class="form-text text-muted">e.g. Biology, Computer Science, Intellectual Property, Nursing, Psychology.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputLocation">City</label>
                            <input v-model="model.location" type="text" id="inputLocation" class="form-control" aria-describedby="locationHelpBlock" required>
                            <small id="locationHelpBlock" class="form-text text-muted">e.g. London, Manchester, Birmingham.</small>
                        </div>
                    
                        <button type="submit" class="btn btn-action w-25">Save</button>
                        <button type="button" class="btn btn-link" @click="model.editing = !model.editing">Cancel</button>
                    </form>
                </template>
                <template v-else>
                    <div class="cv-item-inner">
                        <!-- EDUCATION -->
                        <template v-if="schema.name === 'education'">
                            <p class="my-1">{{ model.degree }} in {{ model.field_of_study }}</p>
                            <p class="my-1">{{ model.school_name }} - {{ model.location }}</p>
                        </template>
                    </div>
                    <button class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)"><span class="oi oi-delete"></span></button>
                    <button class="btn btn-link btn-sm float-right" @click="model.editing = !model.editing"><span class="oi oi-pencil"></span></button>
                </template>
            </div>
        </script>
    @endverbatim
    
    {{-- development version, includes helpful console warnings --}}
    <script src="//cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    
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
                }
            },
        });
        
        Vue.component('cv-item', {
            template: '#template__cv_item',
            props: ['schema', 'model', 'index'],
            methods: {
                formatMonthYear (start) {
                    return start ?
                          moment().year(this.item.start_year).month(this.item.start_month).date(1).format('MMMM YYYY')
                        : moment().year(this.item.end_year).month(this.item.end_month).date(1).format('MMMM YYYY');
                },
            },
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
                        type: 'month-year',
                        model: 'start_date',
                        required: true,
                    },
                    {
                        type: 'month-year',
                        model: 'end_date',
                    },
                ],
            },
        ]
        
        let data = {
            model: {
                education: [
                    {
                        editing: false,
                        degree: 'PhD',
                        field_of_study: 'Computer Science',
                        school_name: 'MIT',
                        location: 'Boston, MA, USA',
                    }
                ]
            },
            schemas: schemas,
        }
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
    </script>
@endsection