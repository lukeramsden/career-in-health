<template>
    <div class="container-fluid my-4">
        <template v-if="loaded">
            <div class="row">
                <div class="col-12 col-lg-4 order-lg-last">
                    <div class="card card-custom-material card-custom-material-hover position-sticky top-4"
                         id="job-listings-table-filter-card">
                        <div class="card-body">
                            <input class="form-control input-material" placeholder="Search" id="input-query"
                                   type="text" v-model="query.text">
                            <v-select placeholder="Status" id="input-status"
                                      class=""
                                      :allowClear="true"
                                      v-model="query.status"
                                      :options="statusDropdown"></v-select>
                            <v-select placeholder="Address" id="input-address"
                                      class=""
                                      v-model="query.address" multiple
                                      :options="addressDropdown"></v-select>
                            <v-select placeholder="Sort By" id="input-sortBy"
                                      class=""
                                      v-model="sortBy"
                                      :options="sortingDropdown"></v-select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="card card-custom-material" id="job-listings-table-results">
                        <div class="card-header text-muted">
                            <p class="float-left mb-0 mt-2">Viewing {{pageItemsCount}} results</p>
                            <pagination v-model="page" :last-page="lastPage" custom-input
                                        class="float-right"></pagination>
                        </div>
                        <template v-for="result in results">
                            <li class="list-group-item" v-bind:key="result.id">
                                <div class="row">
                                    <div class="col-12 col-lg-9">
                                        <p class="mb-1">{{result.title}}</p>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="btn-group btn-group-vertical btn-group-full">
                                            <a :href="viewListing(result)" class="btn btn-primary">View</a>
                                            <a :href="editListing(result)" class="btn btn-action">Edit</a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div id="small-details">
                                            <div>
                                                <p>
                                                    <span v-if="result.status_name === 'Published'"
                                                          class="badge badge-primary badge-pill p-2 px-3">
                                                        <span class="oi oi-check"></span>
                                                        Published
                                                    </span>
                                                    <span v-else-if="result.status_name === 'Closed'"
                                                          class="badge badge-danger badge-pill p-2 px-3">
                                                        <span class="oi oi-ban"></span>
                                                        Closed {{ result.closed_at | dateDiff }}
                                                    </span>
                                                    <span v-else-if="result.status_name === 'Draft'"
                                                          class="badge badge-secondary badge-pill p-2 px-3">
                                                        <span class="oi oi-bookmark"></span>
                                                        Draft
                                                    </span>
                                                    <span v-else
                                                          class="badge badge-info badge-pill p-2 px-3">
                                                        {{ result.status_name }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <p>
                                                    <span class="oi oi-map-marker mr-1"></span>
                                                    {{ result.address.name }}
                                                </p>
                                            </div>
                                            <!--<div>-->
                                            <!--<p>-->
                                            <!--@money($jobListing->min_salary * 100, 'GBP') - @money($jobListing->max_salary * 100, 'GBP')-->
                                            <!--</p>-->
                                            <!--</div>-->
                                            <div>
                                                <p>
                                                    <span class="oi oi-calendar"></span>
                                                    <span class="text-muted">Last Updated</span> {{ result.last_edited |
                                                    dateDiff }}
                                                </p>
                                            </div>
                                            <div>
                                                <p>
                                                    <span class="oi oi-calendar"></span>
                                                    <span class="text-muted">Created</span> {{ result.created_at |
                                                    dateDiff }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        <loading-icon v-else></loading-icon>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex';
    import vSelect from 'vue-select';
    import Pagination from './Pagination';

    function getSortFunction(idx) {
        switch (idx) {
            case 1:
                return (a, b) => {
                    return moment.utc(a.created_at).isAfter(b.created_at);
                };
            case 2:
                return (a, b) => {
                    return moment.utc(a.created_at).isBefore(b.created_at);
                };
            default:
                return () => 0;
        }
    }

    export default {
        components: {
            vSelect,
            Pagination,
        },
        data() {
            return {
                query: {
                    text: '',
                    status: null,
                    address: [],
                },
                sortBy: null,
                loaded: false,
                page: 0,
                lastPage: 0,
                perPage: 10,
                statusDropdown: [
                    {label: 'Draft', value: 0},
                    {label: 'Published', value: 1},
                    {label: 'Closed', value: 2},
                ],
                sortingDropdown: [
                    {label: 'Default', value: 0},
                    {label: 'Oldest First', value: 1},
                    {label: 'Newest First', value: 2},
                ],
            };
        },
        mounted() {
            axios
                .post(route('job-listing.index.get'))
                .then(res => {
                    if (res.data.success)
                        this.$store.commit('JobListingsTableModule/create', res.data.models);
                    else
                        throw new Error('Could not load listings.');
                })
                .catch(e => {
                    console.error(e);
                    toastr.error('Could not load listings.');
                })
                .then(() => {
                    this.loaded = true;
                });
        },
        filters: {
            dateDiff(val) {
                if (!val) return '';
                return moment.utc(val).local().fromNow();
            }
        },
        computed: {
            ...mapState('JobListingsTableModule', {
                listings: 'items',
            }),
            ...mapGetters({}),
            addressDropdown() {
                return _
                    .chain(this.listings || [])
                    .map(o => {
                        return {label: o.address.name, value: o.address.id};
                    })
                    .uniqBy('value')
                    .value()
                    ;
            },
            matchingResults() {
                // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
                const preparedQuery = fuzzaldrin.prepareQuery(this.query.text);
                // We use this to keep track of the similarity for each option.
                const scores = {};

                return _
                    .chain(this.listings || [])
                    .filter(o => {
                        if (this.query.status)
                            if (o.status_name !== this.query.status.label)
                                return false;

                        if (this.query.address.length > 0)
                            if (!_.includes(_.map(this.query.address, 'value'), o.address.id))
                                return false;

                        return true;
                    })
                    // Score each option & create a new array out of them.
                    .map((o, idx) => {
                        // See how well each field compares to the query.
                        const fieldScores = [
                            o.title,
                            // o.description,
                        ].map(field => fuzzaldrin.score(field, this.query.text, {preparedQuery}));

                        scores[o.id] = Math.max(...fieldScores);
                        return o;
                    })
                    // Remove anything with a really low score.
                    .filter(o => this.query.text ? scores[o.id] > 1 : true)
                    .value()
                    // Finally, sort by the highest score.
                    .sort((a, b) => scores[b.id] - scores[a.id])
                    ;
            },
            results() {
                return _
                    .chain(this.matchingResults)
                    .thru(arr => this.sortBy ? arr.slice().sort(getSortFunction(this.sortBy.value)) : arr)
                    .chunk(this.perPage)
                    .nth(this.page)
                    .value();
            },
            resultsCount() {
                return this.matchingResults.length;
            },
            pageItemsCount() {
                const first = this.page * this.perPage + 1;
                const last = Math.min((this.page * this.perPage) + this.perPage, this.resultsCount);
                return `${first}-${last} of ${this.resultsCount}`;
            },
        },
        watch: {
            matchingResults(newVal) {
                if (!newVal) return;
                this.lastPage = Math.ceil(newVal.length / this.perPage) - 1;
                this.page = _.clamp(0, this.lastPage);
            },
        },
        methods: {
            viewListing(listing) {
                return route('job-listing.show', {jobListing: listing.id});
            },
            editListing(listing) {
                return route('job-listing.edit', {jobListing: listing.id});
            },
        },
    };
</script>

<style lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';

    #small-details {
        display: block;
        margin: 0;

        p {
            margin: 0;
        }

        div {
            margin-top: 0.8rem;
            display: inline-block;

            &:not(:first-child) {
                margin-left: 1rem;
            }
        }
    }

    .dropdown.v-select {
        border: none;
        border-radius: 4px;
        color: #495057;

        .dropdown-toggle {
            background-color: #fff !important;
            padding: 1rem !important;
            border: none !important;
            /*border-radius: 0;*/

            &::after {
                content: unset;
                display: none;
            }
        }

        input[type="search"], input[type="search"]:focus, .selected-tag {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        input[type="search"]::placeholder {
            color: #999 !important;
        }

        .selected-tag {
            background-color: #f9834e !important;
            padding: 0.3rem 0.6rem !important;
            color: #fff !important;
            margin-right: 0.15rem !important;
            margin-bottom: 0.15rem !important;
        }

        &.single {
            .selected-tag {
                background-color: transparent !important;
                color: #333 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        &::placeholder {
            color: #999;
        }
    }

    #job-listings-table-filter-card {
        .card-body {
            padding: 0 !important;

            & > * {
                border: none;

                &:first-child {
                    &, & > .dropdown-toggle {
                        border-radius: 0;
                        border-bottom: 1px solid #ced4da;
                    }
                }

                &:last-child {
                    &, & > .dropdown-toggle {
                        border-top-left-radius: 0;
                        border-top-right-radius: 0;
                    }
                }

                &:not(:first-child):not(:last-child) {
                    &, & > .dropdown-toggle {
                        border-radius: 0;
                        border-bottom: 1px solid #ced4da;
                    }
                }
            }

            .input-material {
                padding: 1rem;
                margin: 0 !important;
                width: 100% !important;

                &::placeholder {
                    color: #999;
                }
            }
        }
    }
</style>