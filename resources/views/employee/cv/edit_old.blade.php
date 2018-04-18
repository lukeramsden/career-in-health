@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row p-0 m-0">
            <div class="col-md-8">
                <education v-bind:items="education"></education>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <style>

    </style>
@endsection
@section('script')
    {{-- development version, includes helpful console warnings --}}
    <script src="//cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    
    {{-- TEMPLATES --}}
    {{-- EDUCATION --}}
    @verbatim
        <script type="text/x-template" id="template__education__item">
            <div class="cv-item cv-item-education">
                <template v-if="editing">
                    <form @submit.prevent="editing = !editing">
                        <div class="form-group">
                            <label for="inputDegree">Degree</label>
                            <input v-model="item.degree" type="text" id="inputDegree" class="form-control" aria-describedby="degreeHelpBlock" required>
                            <small id="degreeHelpBlock" class="form-text text-muted">e.g. Diploma, Bachelor's, PhD.</small>
                        </div>

                        <div class="form-group">
                            <label for="inputSchoolName">College or University</label>
                            <input v-model="item.school_name" type="text" id="inputSchoolName" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputFieldOfStudy">Field Of Study</label>
                            <input v-model="item.field_of_study" type="text" id="inputFieldOfStudy" class="form-control" aria-describedby="fieldOfStudyHelpBlock" required>
                            <small id="fieldOfStudyHelpBlock" class="form-text text-muted">e.g. Biology, Computer Science, Intellectual Property, Nursing, Psychology.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputLocation">City</label>
                            <input v-model="item.location" type="text" id="inputLocation" class="form-control" aria-describedby="locationHelpBlock" required>
                            <small id="locationHelpBlock" class="form-text text-muted">e.g. London, Manchester, Birmingham.</small>
                        </div>
                    
                        <button type="submit" class="btn btn-action w-25">Save</button>
                        <button type="button" class="btn btn-link" @click="editing = !editing">Cancel</button>
                    </form>
                </template>
                <template v-else>
                    <div class="cv-item-inner">
                        <p class="my-1">{{ item.degree }} in {{ item.field_of_study }}</p>
                        <p class="my-1">{{ item.school_name }} - {{ item.location }}</p>
                        <p class="my-1">
                            {{ formatDate(true) }}
                            to
                            {{ formatDate(false) }}
                        </p>
                    </div>
                    <button class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)"><span class="oi oi-delete"></span></button>
                    <button class="btn btn-link btn-sm float-right" @click="editing = !editing"><span class="oi oi-pencil"></span></button>
                </template>
            </div>
        </script>
        <script type="text/x-template" id="template__education">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="row align-content-center">
                        <div class="col-12">
                            <h4 class="d-inline-block">Education</h4>
                            <button @click="open = !open" class="btn btn-link float-right"><span class="oi" v-bind:class="{ 'oi-chevron-bottom': !open, 'oi-chevron-top': open }"></span></button>
                        </div>
                        <div class="col-12">
                            <template v-if="open">
                                <div v-for="(item, index) in items">
                                    <education-item v-bind:item="item" v-bind:index="index" v-on:delete-cv-item="del"></education-item>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </script>
    @endverbatim
    
    <script>
        let data = {
            education: [
                {
                    degree: 'PhD',
                    field_of_study: 'Computer Science',
                    school_name: 'MIT',
                    location: 'Boston, MA, USA',
                    start_month: 3,
                    start_year: 2012,
                    end_month: 8,
                    end_year: 2016
                },
            ]
        };

        Vue.component('education-item', {
            data () {
                return {
                    editing: false,
                }
            },
            props: ['item', 'index'],
            template: '#template__education__item',
            methods: {
                formatDate (start) {
                    return start ?
                          moment().year(this.item.start_year).month(this.item.start_month).date(1).format('MMMM YYYY')
                        : moment().year(this.item.end_year).month(this.item.end_month).date(1).format('MMMM YYYY');
                }
            }
        })
        
        Vue.component('education', {
            data () {
                return {
                    open: true,
                }
            },
            props: ['items'],
            template: '#template__education',
            methods: {
                del (i) {
                    this.items.splice(i, 1);
                }
            }
        })
        
        const app = new Vue({
            el: '#app',
            data: data
        });
    </script>
@endsection