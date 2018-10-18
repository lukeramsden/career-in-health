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
                                    <option v-for="role in jobRoles" >{{role.name}}</option>
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label for="where-input">Where</label>
                                <select
                                    id="where-input"
                                    name="where"
                                    class="custom-select">
                                    <!--<option-->
                                    <!--{{ old('where', Request::get('where')) != null ? '' : 'selected' }} disabled></option>-->
                                    <!--@foreach (\App\Location::getAllLocations() as $loc)-->
                                    <!--<option-->
                                    <!--{{ $loc->id == old('where', Request::get('where')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>-->
                                    <!--@endforeach-->
                                </select>
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
                    <div id="search-results">
                    </div>
                </div>
            </div>
        </template>
        <loading-icon v-else></loading-icon>
    </div>
</template>

<script>
    import Awesomplete from 'awesomplete';
    import WNumb from 'wnumb';
    import NoUiSlider from 'nouislider';
    import 'awesomplete/awesomplete.css';
    import 'nouislider/distribute/nouislider.css';

    import vSelect from 'vue-select';

    export default {
        components: {
            vSelect,
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
                loaded: true,
                jobRoles: [{id: 1, name: 'nurse'}],
            };
        },
        mounted() {
            const vm = this;
            let $what = $('#what-input');
            const whatDropdown = new Awesomplete('#what-input');

            $what.on('awesomplete-selectcomplete', function (event) {
                // $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                console.log(event);
                vm.query.what = event.target.value;
                whatDropdown.close();
            });

        },
        destroyed() {
            $('#what-input').off().destroy();
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