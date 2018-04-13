@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <h1 class="my-5">My CV</h1>
        <education v-bind:items="education"></education>
    </div>
@endsection
@section('stylesheet')
    <style>

    </style>
@endsection
@section('script')
    {{-- TEMPLATES --}}
    @verbatim
        <script type="text/x-template" id="template__education__item">
            <div>
                <div class="row">
                    <div class="col-11">
                        <template v-if="editing">
                            <form>
                                <input class="form-control" type="text" v-model="item.degree">
                            </form>
                        </template>
                        <template v-else>
                            <p>{{ item.degree }}</p>
                        </template>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-outline-primary btn-sm" @click="editing = !editing">{{ editing ? 'Save' : 'Edit' }}</button>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/x-template" id="template__education">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="row align-content-center">
                        <div class="col-11">
                            <h3>Education</h3>
                        </div>
                        <div class="col-1">
                            <button @click="open = !open" class="btn btn-outline-primary"><span class="oi" v-bind:class="{ 'oi-plus': !open, 'oi-minus': open }"></span></button>
                        </div>
                        <div class="col">
                            <template v-if="open">
                                <div v-for="item in items" class="my-2">
                                    <education-item v-bind:item="item"></education-item>
                                    <hr>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </script>
    @endverbatim
    {{-- development version, includes helpful console warnings --}}
    <script src="//cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script>
        let data = {
            education: [
                {degree: 'Computer Science'},
                {degree: 'Biology'},
                {degree: 'Psychology'},
            ]
        };

        Vue.component('education-item', {
            data () {
                return {
                    editing: false,
                }
            },
            props: ['item'],
            template: '#template__education__item',
        })
        
        Vue.component('education', {
            data () {
                return {
                    open: false,
                }
            },
            props: ['items'],
            template: '#template__education',
        })
        
        const app = new Vue({
            el: '#app',
            data: data
        });
    </script>
@endsection