@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <job_listing-form :model="model" :url="url" :create-new="createNew"></job_listing-form>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js"></script>
    
    @verbatim
        <script type="text/x-template" id="template__job_listing-form">
            <form @submit="submit" :action="url" method="post">
                @endverbatim
                {{ csrf_field() }}
                @verbatim
                    
                    <div class="card-columns smaller-card-columns">
                        <div class="card card-custom">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Title (<span class='text-action'>*</span>)</label>
                                    <input type="text" class="form-control" v-model="model.title" name="title"
                                           maxlength="120" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card card-custom">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="model.description" class="form-control" name="description"
                                              rows="25" maxlength="3000" :required="!model.savingForLater"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card card-custom">
                            <div class="card-body">
                                <select2 v-model="model.address_id" name="address_id" :required="!model.savingForLater">
                                    <option :value="null">-</option>
                                    <option v-for="address in addresses" :value="address.id">{{ address.name }}</option>
                                </select2>
                                @endverbatim
                                <p class="text-muted mb-0 mt-2">This will be the address that you want to find staff
                                    for. If you haven't created an address yet, <a class="text-action"
                                                                                   href='{{ route('address.create') }}'>click
                                        here</a> to create one.</p>
                                @verbatim
                            </div>
                        </div>
                        
                        @endverbatim
                        <div class="card card-custom">
                            <div class="card-body">
                                <select2 v-model="model.job_role" name="job_role" :required="!model.savingForLater">
                                    <option :value="null">-</option>
                                    @foreach(\App\JobRole::all() as $job)
                                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select2>
                            </div>
                        </div>
                        
                        <div class="card card-custom">
                            <div class="card-body">
                                @foreach(\App\JobListing::$settings as $id => $setting)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="{{ $id }}"
                                               name="setting" v-model="model.setting" id="setting-{{ $id }}"
                                               :required="!model.savingForLater">
                                        <label class="custom-control-label"
                                               for="setting-{{ $id }}">{{ $setting }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="card card-custom">
                            <div class="card-body">
                                @foreach(\App\JobListing::$types as $id => $type)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="{{ $id }}" name="type"
                                               v-model="model.type" id="type-{{ $id }}"
                                               :required="!model.savingForLater">
                                        <label class="custom-control-label" for="type-{{ $id }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @verbatim
                            
                            <div class="card card-custom">
                                <div class="card-body">
                                    <label>Minimum/Maximum Salary</label>
                                    <div id="salary-slider"></div>
                                    <input type="hidden" name="min_salary" id="min-salary-input"
                                           v-model="model.min_salary">
                                    <input type="hidden" name="max_salary" id="max-salary-input"
                                           v-model="model.max_salary">
                                </div>
                            </div>
                            
                            <div class="card card-custom">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="inputSaveForLater"
                                                   name="savingForLater" v-model="model.savingForLater">
                                            <label class="custom-control-label" for="inputSaveForLater">Save For
                                                Later?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        @endverbatim
                        @if($edit)
                            <div class="card card-custom-material card-custom-material-hover">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <input type="text" class="form-control" v-model="model.close_reason">
                                    </div>
                                </div>
                                <div class="card-footer p-0">
                                    <button v-if="model.closed_at == null"
                                            v-on:click.stop.prevent="closeListing"
                                            class="btn btn-danger btn-block">
                                        <span class="oi oi-ban"></span>
                                        Close
                                    </button>
                                    <template v-else>
                                        <button v-on:click.stop.prevent="closeListing"
                                                class="btn btn-primary btn-block">
                                            Save Reason
                                        </button>
                                        <button v-on:click.stop.prevent="openListing"
                                                class="btn btn-info btn-block mt-0">
                                            Re-Open
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endif
                        @verbatim
                            
                            <div class="card card-custom-material card-custom-material-hover card-custom-no-top-bar">
                                <div class="btn-group btn-group-full btn-group-vertical">
                                    <button type="submit"
                                            class="btn btn-action">{{ createNew ? 'Create' : 'Save' }}</button>
                                    
                                    @endverbatim
                                    @if($edit)
                                        <a href="{{ route('job-listing.show', $jobListing) }}" class="btn btn-primary">Show</a>
                                        <a href="{{ route('job-listing.destroy', [$jobListing]) }}"
                                           onclick="return confirm('Are you sure?');"
                                           class="btn btn-danger">Delete</a>
                                    @endif
                                    @verbatim
                                </div>
                            </div>
                    </div>
            </form>
        </script>
        
        <script type="text/x-template" id="template__select2">
            <select :name="name">
                <slot></slot>
            </select>
        </script>
    @endverbatim
    
    <script>
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "positionClass": "toast-top-right",
            "progressBar": true,
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

        Vue.component('job_listing-form', {
            template: '#template__job_listing-form',
            props: ['model', 'url', 'method', 'createNew'],
            methods: {
                submit(event) {
                    if (this.createNew) {
                        return true;
                    }

                    axios({
                        url: this.url,
                        method: 'post',
                        data: this.model,
                    })
                        .then((response) => {
                            if (response.data.success) {
                                toastr.success('Updated!')
                                if (_.get(response, 'data.model.published', false))
                                    toastr.info('This listing has been published successfully.<br><a href="{{ route('job-listing.show', ['jobListing' => $jobListing]) }}" class="btn btn-action btn-sm mt-1">View Listing</a>');
                                else
                                    toastr.info('This listing is not public.')
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            _.forIn(
                                error.response.data.errors,
                                (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                            );
                        });

                    event.preventDefault();
                    return false;
                },
                closeListing(e) {
                    var self = this;
                    var $self = $(e.target);
                    $self.prop('disabled', true);
                    axios
                        .post('{{ route('job-listing.close', $jobListing) }}', {
                            close_reason: self.model.close_reason,
                        })
                        .then(function (res) {
                            if (res.data.success) {
                                self.model = res.data.model;
                            }
                        })
                        .catch(function (e) {
                            console.log(e);
                            toastr.error('Could not close listing.');
                        })
                        .then(function () {
                            $self.prop('disabled', false);
                        })
                },
                openListing(e) {
                    var self = this;
                    var $self = $(e.target);
                    $self.prop('disabled', true);
                    axios
                        .post('{{ route('job-listing.open', $jobListing) }}')
                        .then(function (res) {
                            if (res.data.success) {
                                self.model = res.data.model;
                            }
                        })
                        .catch(function (e) {
                            console.log(e);
                            toastr.error('Could not open listing.');
                        })
                        .then(function () {
                            $self.prop('disabled', false);
                        })
                },
            },
            computed: {
                compAddressId() {
                    return this.model.address_id;
                },
            },
            watch: {
                compAddressId(newVal, oldVal) {
                    this.model.address = _.find(this.addresses, (address) => address.id === parseInt(newVal));
                },
            },
            data() {
                return {
                    addresses: {!! $currentUser->userable->company->addresses->toJson() !!},
                    close_reason: '',
                };
            },
            mounted() {
                const salarySlider = document.getElementById('salary-slider');
                const moneyFormatter = wNumb({
                    decimals: 0,
                    thousand: ',',
                    prefix: '£'
                });

                noUiSlider.create(salarySlider, {
                    start: [
                        this.model.min_salary || 0,
                        this.model.max_salary || 150000
                    ],
                    step: 500,
                    tooltips: true,
                    margin: 2000,
                    range: {
                        'min': 0,
                        'max': 150000
                    },
                    format: wNumb({
                        decimals: 0,
                        thousand: ',',
                        prefix: '£'
                    }),
                    pips: {
                        mode: 'positions',
                        values: [0, 25, 50, 75, 100],
                        density: 4,
                        format: moneyFormatter
                    }
                });

                const self = this;

                salarySlider.noUiSlider.on('change', function (values, handle) {
                    self.$set(self.model, 'min_salary', moneyFormatter.from(values[0]));
                    self.$set(self.model, 'max_salary', moneyFormatter.from(values[1]));
                });

                $('#salary-slider').find('div.noUi-value:nth-child(30)').html('£150,000+');
            },
        });

        let data = {
            model: {!! $edit ? ($jobListing ?? '{}'):(Session::hasOldInput()?(json_encode(Session::getOldInput())??'{}'):('{}'))!!},
            url: '{{ $edit ? route('job-listing.update', ['jobListing' => $jobListing]) : route('job-listing.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        @if($jobListing->isDraft())
            data.model.savingForLater = true;
        @else
            data.model.savingForLater = false;
        @endif

        const app = new Vue({
                el: '#app',
                data: data,
            });
        
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
    </script>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
    <style>
        .select2 {
            display: block;
        }
        
        .select2-container--default .select2-selection--single {
            border-color: #ced4da;
        }
        
        .custom-checkbox .custom-control-label::before,
        .custom-radio .custom-control-label::before {
            border: 1px solid #495057;
        }
        
        #salary-slider {
            margin: 0 40px 50px 20px;
        }
        
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
    </style>
@endsection