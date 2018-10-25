<template>
    <div class="container-fluid m-0 p-0">
        <template v-if="loaded">
            <div class="row" id="search-row">
                <div class="col-12 col-md-5 col-lg-4 order-md-last" id="search-form-parent">
                    <div id="search-form">
                        <form v-on:submit.stop.prevent="">
                            <div class="form-group">
                                <label for="what-input">What</label>
                                <input
                                    id="what-input"
                                    name="what"
                                    class="form-control"
                                    list="what-list"
                                    autocomplete="false"
                                    v-model="query.what">
                                <datalist id="what-list">
                                    <option v-for="role in jobRoles">{{role.name}}</option>
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label for="where-input">Where</label>
                                <select2 name="where"
                                         id="where-input"
                                         v-model="query.where"
                                         :data="locations">
                                </select2>
                                <!--<select-->
                                <!--id="where-input"-->
                                <!--name="where"-->
                                <!--class="custom-select">-->
                                <!--<option-->
                                <!--{{ old('where', Request::get('where')) != null ? '' : 'selected' }} disabled></option>-->
                                <!--@foreach (\App\Location::getAllLocations() as $loc)-->
                                <!--<option-->
                                <!--{{ $loc->id == old('where', Request::get('where')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>-->
                                <!--@endforeach-->
                                <!--</select>-->
                            </div>

                            <!--<div class="form-group form-group-dropdown">-->
                            <!--<label>Radius (miles)</label>-->
                            <!--<div id="radius-slider"></div>-->
                            <!--<input type="hidden" name="radius" id="radius-input"-->
                            <!--value="{{ old('radius', Request::get('radius', 50)) }}">-->
                            <!--</div>-->

                            <!--<div class="form-group form-group-dropdown">-->
                            <!--<label>Minimum/Maximum Salary</label>-->
                            <!--<div id="salary-slider"></div>-->
                            <!--<input type="hidden" name="min_salary" id="min-salary-input"-->
                            <!--value="{{ old('min_salary', Request::get('min_salary', 0)) }}">-->
                            <!--<input type="hidden" name="max_salary" id="max-salary-input"-->
                            <!--value="{{ old('max_salary', Request::get('max_salary', 150000)) }}">-->
                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--<label>Settings</label>-->

                            <!--@foreach(\App\JobListing::$settings as $id => $setting)-->
                            <!--<div class="custom-control custom-checkbox">-->
                            <!--<input type="checkbox" class="custom-control-input"-->
                            <!--{{ collect(old('setting_filter', Request::get('setting_filter')))->contains($id) ? 'checked':'' }} name="setting_filter[]"-->
                            <!--value="{{ $id }}" id="setting-check{{ $id }}">-->
                            <!--<label class="custom-control-label"-->
                            <!--for="setting-check{{ $id }}">{{ $setting }}</label>-->
                            <!--</div>-->
                            <!--@endforeach-->

                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--<label>Types</label>-->

                            <!--@foreach(\App\JobListing::$types as $id => $type)-->
                            <!--<div class="custom-control custom-checkbox">-->
                            <!--<input type="checkbox" class="custom-control-input"-->
                            <!--{{ collect(old('type_filter', Request::get('type_filter')))->contains($id) ? 'checked':'' }} name="type_filter[]"-->
                            <!--value="{{ $id }}" id="type-check{{ $id }}">-->
                            <!--<label class="custom-control-label"-->
                            <!--for="type-check{{ $id }}">{{ $type }}</label>-->
                            <!--</div>-->
                            <!--@endforeach-->

                            <!--</div>-->
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-8" id="search-results-parent">
                    <div class="float-right">
                        <pagination v-model="page"
                                    :lastPage="lastPage"></pagination>
                    </div>
                    <div id="search-results" v-if="resultsLoaded">
                        <template v-for="result in results">
                            <div class="card card-custom card-job_listing">
                                <div class="card-body">
                                    <a :href="route('company.show', {company: result.company.id})"
                                       class="card-subtitle">
                                        {{result.company.name}}
                                        <verified-badge :company="result.company"/>
                                    </a>
                                    <h4 class="card-title">
                                        {{result.job_role.name}}
                                    </h4>
                                    <h5>
                                        <a :href="route('job-listing.show', {jobListing: result.id})">
                                            {{ result.title }}
                                        </a>
                                    </h5>
                                    <h6>{{ result.setting_name }}</h6>
                                    <div id="small-details">
                                        <div>
                                            <p>
                                                <span class="badge badge-secondary badge-pill p-2 px-3">
                                                    {{ result.type_name }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                <span class="oi oi-map-marker mr-3"></span>
                                                {{result.address.location.name}}
                                                (<b>{{getDistanceBetween(result.address.location.lat_lng, town.lat_lng)}}
                                                </b> miles away)
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                {{(result.min_salary * 100) | currency}} -
                                                {{(result.max_salary * 100) | currency}}
                                            </p>
                                        </div>
                                        <div>
                                            <a class="btn btn-outline-primary"
                                               :href="route('tracking.job-listing.search.click', {jobListing: result.id})">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <loading-icon v-else></loading-icon>
                </div>
            </div>
        </template>
        <loading-icon v-else></loading-icon>
    </div>
</template>

<!--suppress BadExpressionStatementJS -->
<script>
    import {mapGetters, mapState} from 'vuex';
    import Awesomplete from 'awesomplete';
    import WNumb from 'wnumb';
    import NoUiSlider from 'nouislider';
    import 'awesomplete/awesomplete.css';
    import 'nouislider/distribute/nouislider.css';

    import vSelect from 'vue-select';
    import Select2 from './Select2';
    import Pagination from './Pagination';
    import VerifiedBadge from './VerifiedBadge';

    export default {
        components: {
            vSelect,
            Select2,
            Pagination,
            VerifiedBadge,
        },
        data() {
            return {
                query: {
                    what: '',
                    where: null,
                },
                page: 0,
                lastPage: 0,
                perPage: 10,
                pagesLoaded: [],
                totalResults: 0,

                loaded: false,
                resultsLoaded: true,

                town: null,

                whatDropdown: null,
                jobRoles: [],
                locations: [],
            };
        },
        mounted() {
            this.load()
                .then(() => {
                    this.loaded = true;

                    this.$nextTick(() => {
                        let $what = $('#what-input');
                        this.whatDropdown = new Awesomplete('#what-input');

                        let self = this;
                        $what.on('awesomplete-selectcomplete', function (event) {
                            $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                            self.whatDropdown.close();
                        });
                    });
                })
                .catch(e => console.error(e));
        },
        destroyed() {
            $('#what-input').off();
            this.whatDropdown.destroy();
            this.whatDropdown = null;
        },
        methods: {
            route() {
                return route(...arguments);
            },
            async load() {
                this.jobRoles
                    = (await axios.get(route('get-all-job-roles')))['data']
                    .map(o => ({id: o.id, name: o.name}));

                this.locations
                    = (await axios.get(route('get-all-locations')))['data']
                    .map(o => ({id: o.id, text: o.name}));
            },
            search: _.debounce(function () {
                if (!this.query.what || !this.query.where)
                    return;

                console.log('search');

                this.resultsLoaded = false;

                axios
                    .post(route('search.get'), this.searchData)
                    .then(res => {
                        if (res.data.success) {
                            console.log(res);

                            this.$store.commit('SearchModule/create', res.data.models.results.data);

                            this.pagesLoaded.push(this.page);
                            this.pagesLoaded.sort();

                            this.lastPage = res.data.models.results.last_page - 1;
                            this.perPage = res.data.models.results.per_page;
                            this.totalResults = res.data.models.results.total;

                            this.town = res.data.models.town;
                        } else throw new Error('Could not load listings.');
                    })
                    .catch(e => console.error(e))
                    .then(() => {
                        this.$nextTick(() => this.resultsLoaded = true);
                    });
            }, 800, {leading: true, tailing: true}),
            /**
             * Get distance between 2 lat/lng points in miles
             * Either takes 2 objects of type LatLng, or 4 arguments of type number
             *
             * Option 1:
             *
             * LatLng {
             *     lat: number
             *     lng: number,
             * }
             *
             * latlng1, latlng2
             *
             * Option 2:
             *
             * lat1, lng1, lat2, lng2
             */
            getDistanceBetween() {
                const args = [...arguments];
                const calc = (lat1, lon1, lat2, lon2) => {
                    const deg2rad = d => d * 0.017453292519943295; // Math.PI / 180
                    const dLat = deg2rad(lat2 - lat1) / 2;
                    const dLon = deg2rad(lon2 - lon1) / 2;
                    const a =
                        Math.sin(dLat) * Math.sin(dLat) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                        Math.sin(dLon) * Math.sin(dLon)
                    ;
                    return (6371 * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)))) * 0.6213711922; // convert to miles
                };

                // Option 1
                if (args.length === 2) {
                    return calc(
                        args[0].lat, args[0].lng,
                        args[1].lat, args[1].lng);
                }
                // Option 2
                else if (args.length === 4) {
                    return calc(...args);
                } else throw new Error('Wrong number of arguments.');
            },
        },
        computed: {
            ...mapState('SearchModule', {
                items: 'items',
            }),
            ...mapGetters({}),
            searchData() {
                return {
                    ...this.query,
                    page: this.page + 1,
                };
            },
            results() {
                return this.items.slice(this.perPage * this.page, this.perPage * (this.page + 1));
            },
        },
        watch: {
            query: {
                handler(newVal, oldVal) {
                    this.page = 0;
                    this.pagesLoaded = [];
                    this.totalResults = 0;
                    this.$store.commit('SearchModule/clear');
                    this.search();
                },
                deep: true,
            },
            page: {
                handler(newVal, oldVal) {
                    if (!this.pagesLoaded.includes(newVal)) {
                        this.resultsLoaded = false;
                        this.search();
                    }
                },
            },
        },
    }
</script>

<style lang="scss">
    .noUi-tooltip {
        display: none;
    }

    .noUi-active .noUi-tooltip {
        display: block;

    }

    .noUi-handle {
        outline: none;
        border-radius: 0;
    }

    .noUi-value-sub {
        color: #999;
        line-height: 1.8;
    }

    .awesomplete {
        display: block;
    }
</style>