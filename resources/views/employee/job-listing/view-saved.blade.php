@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            @verbatim
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-4 order-lg-last">
                            <div class="card card-custom-material card-custom-material-hover position-sticky top-4" id="application-filter-card">
                                <div class="card-body p-0">
                                    <input class="input input-material w-100 p-3" placeholder="Search" id="input-query"
                                           type="text" v-model="query">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="card card-custom card-listing mb-4" v-for="listing in queryResults">
                                <div class="card-body">
                                    <a :href="route('company.show', {company: listing.company.id})"
                                       class="card-subtitle">
                                        {{listing.company.name}}
                                    </a>
                                    <h4 class="card-title">{{listing.job_role.name}}</h4>
                                    <h5><a
                                        :href="permalink(listing)">{{ listing.title }}</a>
                                    </h5>
                                    <h6>{{ listing.setting_name }}</h6>
                                    <div id="small-details">
                                        <div>
                                            <p><span
                                                class="badge badge-secondary badge-pill p-2 px-3">{{ listing.type_name }}</span>
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                {{ listing.min_salary_formatted }} - {{ listing.max_salary_formatted }}
                                            </p>
                                        </div>
                                        <div v-if="listing.closed_at != null">
                                            <p>
                                                <span class="badge badge-danger p-2 px-3">
                                                    <span class="oi oi-ban"></span>
                                                    Closed
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                <a
                                                :href="permalink(listing)"
                                                class="btn btn-primary btn-sm">View</a>
                                                <button
                                                class="btn btn-golden btn-sm"
                                                v-on:click.stop.prevent="remove(listing.id, $event)"><span
                                                    class="oi oi-star"></span> Remove
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
    
    <script>
        toastr.options = {
            'closeButton': true,
            'newestOnTop': false,
            'positionClass': 'toast-top-right',
            'progressBar': true,
        };

        let data = {
            savedJobListings: {!! json_encode($jobListings->map(returns('jobListing'))) !!},
            query: '',
        };

        const app = new Vue({
            el: '#app',
            data: data,
            computed: {
                queryResults() {
                    // Don't bother with scoring anything if the query is empty.
                    if (!this.query) return this.savedJobListings;

                    // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
                    const preparedQuery = fuzzaldrin.prepareQuery(this.query);
                    // We use this to keep track of the similarity for each option.
                    const scores = {};

                    return this.savedJobListings
                    // Score each option & create a new array out of them.
                        .map((item, index) => {
                            // See how well each field compares to the query.
                            const fieldScores = [
                                item.title,
                                item.company.name,
                                item.setting_name,
                                item.type_name,
                                item.job_role.name,
                                item.address.name,
                                // Creating an array of fields and mapping is easier than writing
                                // fz.score(...) six times. Same idea.
                                // Scores are a non-normalized number
                                // representing how similar the query is to the field.
                            ].map(field => fuzzaldrin.score(field, this.query, {preparedQuery}));

                            // Store the highest score for this option
                            // so we can compare it to other options.
                            scores[item.id] = Math.max(...fieldScores);

                            return item;
                        })
                        // Remove anything with a really low score.
                        // You might want to play around with this.
                        .filter(item => scores[item.id] > 1)
                        // Finally, sort by the highest score.
                        .sort((a, b) => scores[b.id] - scores[a.id])
                        ;
                }
            },
            methods: {
                permalink(listing) {
                    return route('job-listing.show', {jobListing: listing.id});
                },
                remove(id, e) {
                    var self = this;
                    var $self = $(e.target);
                    $self.prop('disabled', true);
                    axios
                        .post(route('employee.unsave-job-listing', {jobListing: id}))
                        .then(function (resp) {
                            if (resp.data.success)
                                self.savedJobListings =
                                    self.savedJobListings.filter(a => a.id !== id);
                        })
                        .catch(function (e) {
                            console.log(e);
                            toastr.error('Could not remove.');
                        })
                        .then(function () {
                            $self.prop('disabled', false);
                        })
                },
            },
        });
        
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
    </script>
@endsection