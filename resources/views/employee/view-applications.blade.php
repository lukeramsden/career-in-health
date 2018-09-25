
@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            @verbatim
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-4 order-lg-last">
                            <div class="card card-custom-material position-sticky top-4" id="application-filter-card">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script>
        toastr.options = {
            'closeButton': true,
            'newestOnTop': false,
            'positionClass': 'toast-top-right',
            'progressBar': true,
        };

        let data = {
            applications: {!! json_encode($applications->get()) !!},
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection