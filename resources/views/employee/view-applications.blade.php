
@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            @verbatim
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-4 order-lg-last">
                            <div class="card card-custom card-custom-material position-sticky top-4" id="application-filter-card">
                                <div class="card-body p-0">
                                    <input class="input input-material w-100 p-3" placeholder="Search" id="input-query"
                                           type="text" v-model="query">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="table-responsive">
                                <table class="table w-100">
                                    <thead class="thead-primary text-light">
                                    <tr>
                                        <th scope="col">Listing Title</th>
                                        <th scope="col">Company Name</th>
                                        <th scope="col">Cover Letter</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="result of queryResults">
                                        <td class="text-one-line">{{result.job_listing.title}}</td>
                                        <td class="text-one-line">{{result.job_listing.company.name}}</td>
                                        <td class="text-one-line" v-if="result.custom_cover_letter">{{result.custom_cover_letter}}</td>
                                        <td class="text-one-line" v-else><span class="text-muted font-italic">No cover letter</span></td>
                                        <td class="text-one-line">{{result.status_name}}</td>
                                        <td class="text-one-line"><a :href="result.permalink" class="btn btn-action">View</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endverbatim
        </div>
    </div>
@endsection

@section('script')
    @verbatim
        <script type="text/x-template" id="template__select2">
            <select :name="name">
                <slot></slot>
            </select>
        </script>
    @endverbatim
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js"></script>
    
    <script>
        toastr.options = {
            'closeButton': true,
            'newestOnTop': false,
            'positionClass': 'toast-top-right',
            'progressBar': true,
        };

        Vue.component('select2', {
            template: '#template__select2',
            props: ['name', 'options', 'value'],
            mounted() {
                const self = this;
                $(this.$el)
                    .select2({ // init select2
                        dropdownAutoWidth: true,
                        width: 'auto'
                    })
                    .val(this.value)
                    .trigger('change')
                    // emit event on change.
                    .on('change', function () {
                        self.$emit('input', this.value)
                    })
            },
            watch: {
                value(value) {
                    // update value
                    $(this.$el)
                        .val(value)
                        .trigger('change')
                },
                options(options) {
                    // update options
                    $(this.$el).empty().select2({data: options})
                }
            },
            destroyed() {
                $(this.$el).off().select2('destroy')
            }
        });

        let data = {
            applications: {!! json_encode($applications) !!},
            query: '',
        };

        const app = new Vue({
            el: '#app',
            data: data,
            computed: {
                queryResults() {
                    // Don't bother with scoring anything if the query is empty.
                    if (!this.query) return this.applications;

                    // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
                    const preparedQuery = fuzzaldrin.prepareQuery(this.query);
                    // We use this to keep track of the similarity for each option.
                    const scores = {};

                    return this.applications
                    // Score each option & create a new array out of them.
                        .map((application, index) => {
                            // See how well each field compares to the query.
                            const fieldScores = [
                                application.job_listing.company.name,
                                application.job_listing.title,
                                application.custom_cover_letter,
                                // Creating an array of fields and mapping is easier than writing
                                // fz.score(...) six times. Same idea.
                                // Scores are a non-normalized number
                                // representing how similar the query is to the field.
                            ].map(field => fuzzaldrin.score(field, this.query, {preparedQuery}));

                            // Store the highest score for this option
                            // so we can compare it to other options.
                            scores[application.id] = Math.max(...fieldScores);

                            return application;
                        })
                        // Remove anything with a really low score.
                        // You might want to play around with this.
                        .filter(application => scores[application.id] > 1)
                        // Finally, sort by the highest score.
                        .sort((a, b) => scores[b.id] - scores[a.id])
                        ;
                }
            }
        });
        
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
    
    </script>
@endsection
@section('stylesheet')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

{{--@extends('layouts.app')--}}
{{--@section('content')--}}
    {{--<div class="container-fluid">--}}
        {{--<table class="table table-striped table-hover" id="applications"--}}
        {{--data-order="[[ 4, &quot;desc&quot; ]]">--}}
            {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>--}}
                    {{--<th scope="col" data-searchable="true" data-orderable="true">Company</th>--}}
                    {{--<th scope="col" data-searchable="true" data-orderable="true">Title</th>--}}
                    {{--<th scope="col" data-searchable="true" data-orderable="true">Position</th>--}}
                    {{--<th scope="col" data-searchable="true" data-orderable="true">Date Applied</th>--}}
                    {{--<th scope="col" data-searchable="true" data-orderable="true">Status</th>--}}
                    {{--<th scope="col" data-searchable="false" data-orderable="false" data-width="100px"></th>--}}
                {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
                {{--@foreach($applications as $application)--}}
                    {{--<tr>--}}
                        {{--<td></td>--}}
                        {{--<td data-search="{{ $application->job_listing->company->name }}">--}}
                            {{--<p><a href="{{ route('company.show', [$application->job_listing->company]) }}">{{ $application->job_listing->company->name }}{!!verified_badge($application->job_listing->company)!!}</a></p>--}}
                        {{--</td>--}}
                        {{--<td data-search="{{ $application->job_listing->title }}">--}}
                            {{--<p>{{ str_limit($application->job_listing->title, 80) }}</p>--}}
                        {{--</td>--}}
                        {{--<td data-search="{{ $application->job_listing->jobRole->name }}">--}}
                            {{--<p>{{ $application->job_listing->jobRole->name }}</p>--}}
                        {{--</td>--}}
                        {{--<td data-order="{{ $application->created_at->timestamp }}">--}}
                            {{--<p>{{ $application->created_at->toFormattedDateString() }}</p>--}}
                        {{--</td>--}}
                        {{--<td data-search="{{ \App\JobListingApplication::$statuses[$application->status ?? 0] }}">--}}
                            {{--<p>{{ \App\JobListingApplication::$statuses[$application->status ?? 0] }}</p>--}}
                        {{--</td>--}}
                        {{--<td>--}}
                            {{--<div class="btn-group btn-group-sm" role="group">--}}
                                {{--<a href="{{ route('job-listing.show', [$application->job_listing]) }}" class="btn btn-link">View Listing</a>--}}
                                {{--<a href="{{ route('job-listing.application.show', [$application]) }}" class="btn btn-link">Edit</a>--}}
                            {{--</div>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
            {{--</tbody>--}}
        {{--</table>--}}
    {{--</div>--}}
{{--@endsection--}}
{{--@section('stylesheet')--}}
    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.css"/>--}}
{{--@endsection--}}
{{--@section('script')--}}
    {{--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.js"></script>--}}
    {{----}}
    {{--<script>--}}
        {{--$(function() {--}}
            {{--$('#applications').DataTable({--}}
                {{--responsive: true,--}}
                {{--stateSave: true,--}}
                {{--pageLength: 15,--}}
                {{--lengthMenu: [15, 15 * 2, 15 * 3, 15 * 4, 15 * 5],--}}
                {{--stateDuration: 60 * 5, // 5 minutes--}}
                {{--language: {--}}
                    {{--paginate: {--}}
                        {{--previous: "&lt;",--}}
                        {{--next: "&gt;",--}}
                    {{--},--}}
                {{--},--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
{{--@endsection--}}
