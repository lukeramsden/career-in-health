<template>
    <div class="container-fluid m-0 p-0">
        <template v-if="loaded">
            <div class="row" id="search-row">
                <div class="col-12 col-md-5 col-lg-4 order-md-last" id="search-form-parent">
                    <div id="search-form">
                        <form v-on:submit.stop.prevent="">
                            <div class="form-group">
                                <label for="what-input">What (required)</label>
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
                                <label for="where-input">Where (required)</label>
                                <select2 name="where"
                                         id="where-input"
                                         v-model="query.where"
                                         :data="locations">
                                </select2>
                            </div>

                            <div class="form-group form-group-dropdown">
                                <label>Radius (miles)</label>
                                <div id="radius-slider"></div>
                            </div>

                            <div class="form-group form-group-dropdown">
                                <label>Minimum/Maximum Salary</label>
                                <div id="salary-slider"></div>
                            </div>

                            <div class="form-group">
                                <label>Settings</label>

                                <div v-for="(setting, idx) in settings"
                                     class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           v-model="query.setting_filter"
                                           :value="setting.id" :id="'setting-check-' + setting.id"
                                           :title="setting.name">
                                    <label class="custom-control-label"
                                           :for="'setting-check-' + setting.id">{{ setting.name }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Types</label>

                                <div v-for="(type, idx) in types"
                                     class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           v-model="query.type_filter"
                                           :value="type.id" :id="'type-check-' + type.id"
                                           :title="type.name">
                                    <label class="custom-control-label"
                                           :for="'type-check-' + type.id">{{ type.name }}</label>
                                </div>
                            </div>

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
                <div class="col-12 col-md-7 col-lg-8" id="search-results-parent" style="background-color: #e6e6e6;">
                    <div class="card card-custom card-custom-no-top-bar" v-if="!resultsLoaded || totalResults > 0">
                        <div class="card-header" v-if="lastPage > 0">
                            <p class="float-left mb-0 mt-2">Viewing {{pageItemsCount}} results</p>
                            <pagination class="float-right"
                                        v-model="page"
                                        :disabled="!resultsLoaded"
                                        :lastPage="lastPage"></pagination>
                        </div>
                        <div class="card-body p-0">
                            <div id="search-results" v-if="resultsLoaded">
                                <template v-for="(result, index) in results">
                                    <div class="card card-custom card-custom-no-top-bar card-job_listing">
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
                                                        (<b>{{getDistanceBetween(result.address.location.lat_lng,
                                                        town.lat_lng)}}
                                                    </b> miles away)
                                                    </p>
                                                </div>
                                                <div>
                                                    <p>
                                                        {{result.min_salary | currency}} -
                                                        {{result.max_salary | currency}}
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
                                    <hr v-if="index !== results.length - 1">
                                </template>
                            </div>
                            <loading-icon v-else></loading-icon>
                        </div>
                        <div class="card-footer" v-if="lastPage > 0">
                            <pagination class="float-right"
                                        v-model="page"
                                        :disabled="!resultsLoaded"
                                        :lastPage="lastPage"></pagination>
                        </div>
                    </div>
                    <p v-else class="text-center mt-5"><span class="font-italic text-muted">No Results</span></p>
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
                    radius: 50,
                    min_salary: 0,
                    max_salary: 150000,
                    setting_filter: [],
                    type_filter: [],
                },
                page: 0,
                lastPage: 0,
                perPage: 10,
                pagesLoaded: [],
                totalResults: 0,

                loaded: false,
                resultsLoaded: true,

                town: null,

                jobRoles: [],
                locations: [],
                settings: [],
                type: [],

                whatDropdown: null,
                radiusSlider: null,
                salarySlider: null,

                /**
                 * This is used to track whether the last update to the query
                 * object was by onpopstate or not
                 */
                statePopped: false,
            };
        },
        mounted() {
            console.debug('mounted pre-load');

            this.load()
                .then(() => {
                    this.loaded = true;

                    console.debug('mounted post-load');

                    this.$nextTick(() => {
                        let $what = $('#what-input');
                        this.whatDropdown = new Awesomplete('#what-input');

                        let self = this;

                        $what.on('awesomplete-selectcomplete', function (event) {
                            $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                            self.whatDropdown.close();
                        });

                        this.radiusSlider = document.getElementById('radius-slider');

                        NoUiSlider.create(this.radiusSlider, {
                            start: [this.query.radius],
                            step: 5,
                            tooltips: true,
                            range: {
                                'min': 5,
                                'max': 50
                            },
                            pips: {
                                mode: 'steps',
                                density: 3
                            },
                            format: WNumb({
                                decimals: 0
                            }),
                        });

                        this.radiusSlider.noUiSlider.on('change', function () {
                            self.query.radius = parseInt(this.get());
                        });

                        $('#radius-slider').find('div.noUi-value:nth-child(47)').html('50+');

                        this.salarySlider = document.getElementById('salary-slider');
                        const salaryFormatter = WNumb({
                            decimals: 0,
                            thousand: ',',
                            prefix: '£'
                        });

                        NoUiSlider.create(this.salarySlider, {
                            start: [this.query.min_salary, this.query.max_salary],
                            step: 500,
                            tooltips: true,
                            margin: 2000,
                            range: {
                                'min': 0,
                                'max': 150000,
                            },
                            format: WNumb({
                                decimals: 0,
                                thousand: ',',
                                prefix: '£',
                            }),
                            pips: {
                                mode: 'positions',
                                values: [0, 25, 50, 75, 100],
                                density: 4,
                                format: salaryFormatter,
                            },
                        });

                        this.salarySlider.noUiSlider.on('change', function (values, handle) {
                            self.query.min_salary = salaryFormatter.from(values[0]);
                            self.query.max_salary = salaryFormatter.from(values[1]);
                        });

                        /**
                         * We add the watchers here to stop them being called when we parse the query string
                         */
                        this.$watch('query', function (nval, oval) {
                            console.debug(`watch | query | handler | newVal: ${JSON.stringify(nval)} | oldVal: ${JSON.stringify(oval)}`);

                            if (nval.what && nval.where)
                                this.resultsLoaded = false;

                            this.page = 0;
                            this.pagesLoaded = [];
                            this.totalResults = 0;
                            this.$store.commit('SearchModule/clear');
                            this.search();

                            if (this.radiusSlider)
                                this.radiusSlider.noUiSlider.set([nval.radius]);

                            if (this.salarySlider)
                                this.salarySlider.noUiSlider.set([nval.min_salary, nval.max_salary]);

                        }, {deep: true});

                        this.$watch('page', function (nval, oval) {
                            console.debug(`watch | page | handler | newVal: ${JSON.stringify(nval)} | oldVal: ${JSON.stringify(oval)}`);

                            if (!this.pagesLoaded.includes(nval)) {
                                this.resultsLoaded = false;
                                this.search();
                            }

                            this.updateQueryString();
                        });

                        this.search();
                    });
                })
                .catch(e => console.error(e));
        },
        destroyed() {
            $('#what-input').off();
            this.whatDropdown.destroy();
            this.whatDropdown = null;

            $('#radius-slider').off();
            this.radiusSlider.noUiSlider.destroy();
            this.radiusSlider = null;

            $('#salary-slider').off();
            this.salarySlider.noUiSlider.destroy();
            this.salarySlider = null;
        },
        methods: {
            route() {
                return route(...arguments);
            },
            async load() {
                const [jobRoles, locations, settings, types] = await Promise.all([
                    axios.get(route('get-all-job-roles')),
                    axios.get(route('get-all-locations')),
                    axios.get(route('get-all-listing-settings')),
                    axios.get(route('get-all-listing-types')),
                ]);

                this.jobRoles = _.map(jobRoles['data'], v => ({id: v.id, name: v.name}));
                this.locations = _.map(locations['data'], v => ({id: v.id, text: v.name}));
                this.settings = _.map(settings['data'], (name, id) => ({id, name}));
                this.types = _.map(types['data'], (name, id) => ({id, name}));

                {
                    let before = _.cloneDeep(this.query);
                    this.parseQueryString();
                    let after = _.cloneDeep(this.query);
                    this.resultsLoaded = _.isEqual(before, after);
                }
            },
            updateQueryString() {
                console.debug('updateQueryString');

                const urlParams = new URLSearchParams(location.search);

                for (const x in this.query) {
                    let val = this.query[x];
                    if (x || x === 0) {
                        if (_.isArray(val)) {
                            urlParams.delete(x);
                            if (val.length > 0)
                                for (const y of val)
                                    urlParams.append(x, y);
                        }
                        else urlParams.set(x, val);
                    }
                }

                if (this.page >= 0)
                    urlParams.set('page', this.page + 1);

                if (!this.statePopped) {
                    console.debug('pushState');
                    window.history.pushState({}, "", decodeURIComponent(`${location.pathname}?${urlParams}`));
                } else this.statePopped = false;

                window.onpopstate = (ev) => {
                    console.debug('onpopstate');
                    console.debug(ev);
                    this.statePopped = true;
                    this.parseQueryString();
                }
            },
            parseQueryString() {
                console.debug('parseQueryString');

                const urlParams = new URLSearchParams(location.search);

                for (const x in this.query)
                    this.$set(this.query, x,
                        (_.isArray(this.query[x])
                            ? urlParams.getAll(x)
                            : urlParams.get(x)) || this.query[x]);

                this.page = (_.toSafeInteger(urlParams.get('page')) || 1) - 1;
            },
            search: _.debounce(function () {
                if (!this.query.what || !this.query.where)
                    return;

                console.debug('search');

                this.resultsLoaded = false;

                this.updateQueryString();

                axios
                    .post(route('search.get'), this.searchData)
                    .then(res => {
                        if (res.data.success) {
                            console.debug(res);

                            this.$store.commit('SearchModule/create', res.data.models.results.data);

                            this.pagesLoaded.push(this.page);

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
                        args[1].lat, args[1].lng).toFixed(1);
                }
                // Option 2
                else if (args.length === 4) {
                    return calc(...args).toFixed(1);
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
                /**
                 * This doesn't account for pages before the current loaded page not actually having been requested
                 *
                 * We need to store the results based on page number, instead of just in a basic array
                 *
                 * Scenario: User goes to page, query string specifies page 2
                 *
                 * Current store: [page2-result1, page2-result2, etc...]
                 *
                 * Possible replacement: { 2: [result1, result2, etc...] }
                 */

                let loadedIndex = this.pagesLoaded.indexOf(this.page);

                if (loadedIndex === -1)
                    return [];

                return this.items.slice(this.perPage * loadedIndex, this.perPage * (loadedIndex + 1));
            },
            pageItemsCount() {
                const first = this.page * this.perPage + 1;
                const last = Math.min((this.page * this.perPage) + this.perPage, this.totalResults);
                return `${first}-${last} of ${this.totalResults}`;
            },
        },
        watch: {},
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