@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <advert-form :model="model" :url="url" :create-new="createNew"></advert-form>
    </div>
@endsection
@section('script')
    {{-- development version, includes helpful console warnings --}}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js"></script>

    @verbatim
        <script type="text/x-template" id="template__advert-form">
            <form @submit="submit" :action="url" method="post" >
                @endverbatim
                    {{ csrf_field() }}
                @verbatim
                
                <div class="card-columns" id="advert-edit-card-columns">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" v-model="model.title" name="title" maxlength="120" required>
                            </div>
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea v-model="model.description" class="form-control" name="description" rows="25" maxlength="3000" :required="!model.save_for_later"></textarea>
                            </div>
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            <select2 v-model="model.address_id" name="address_id" :required="!model.save_for_later">
                                <option :value="null">-</option>
                                <option v-for="address in addresses" :value="address.id">{{ address.name }}</option>
                            </select2>
                            @endverbatim
                            <p class="text-muted mb-0 mt-2">This will be the address that you want to find staff for. If you haven't created an address yet, <a class="text-action" href='{{ route('address.create') }}'>click here</a> to create one.</p>
                            @verbatim
                        </div>
                    </div>
    
                    @endverbatim
                    <div class="card card-custom">
                        <div class="card-body">
                            <select2 v-model="model.job_role" name="job_role" :required="!model.save_for_later">
                                <option :value="null">-</option>
                                @foreach(\App\JobRole::all() as $job)
                                    <option value="{{ $job->id }}">{{ $job->name }}</option>
                                @endforeach
                            </select2>
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            @foreach(\App\Advert::$settings as $id => $setting)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" value="{{ $id }}" name="setting" v-model="model.setting" id="setting-{{ $id }}" :required="!model.save_for_later">
                                    <label class="custom-control-label" for="setting-{{ $id }}">{{ $setting }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            @foreach(\App\Advert::$types as $id => $type)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" value="{{ $id }}" name="type" v-model="model.type" id="type-{{ $id }}" :required="!model.save_for_later">
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
                            <input type="hidden" name="min_salary" id="min-salary-input" v-model="model.min_salary">
                            <input type="hidden" name="max_salary" id="max-salary-input" v-model="model.max_salary">
                        </div>
                    </div>
                    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="inputSaveForLater" name="save_for_later" v-model="model.save_for_later">
                                    <label class="custom-control-label" for="inputSaveForLater">Save For Later?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-action btn-block">{{ createNew ? 'Create' : 'Save' }}</button>
                            </div>
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
                        dropdownAutoWidth : true,
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
                    $(this.$el).empty().select2({ data: options })
                }
            },
            destroyed() {
                $(this.$el).off().select2('destroy')
            }
        });
        
        Vue.component('advert-form', {
            template: '#template__advert-form',
            props: ['model', 'url', 'method', 'createNew'],
            methods: {
                submit(event) {
                    if(this.createNew) {
                        return true;
                    }
                    
                    axios({
                        url: this.url,
                        method: 'post',
                        data: this.model,
                    })
                        .then((response) => {
                            console.log(response);
                            if(response.status == 200) {
                                toastr.success('Updated!')
                                switch (_.get(response, 'data.model.status', {{ \App\AdvertStatus::Public }})) {
                                    case {{ \App\AdvertStatus::Draft }}:
                                        toastr.info('This advert is not public.')
                                        break;
                                    case {{ \App\AdvertStatus::Public }}:
                                        toastr.info('This advert has been published successfully.<br><a href="{{ route('advert.show', ['advert' => $advert]) }}" class="btn btn-action btn-sm mt-1">View Advert</a>');
                                        break;
                                }
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
                    addresses: {!! Auth::user()->company->addresses->toJson() !!}
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
                        values: [0,25,50,75,100],
                        density: 4,
                        format: moneyFormatter
                    }
                });
        
                const self = this;
                
                salarySlider.noUiSlider.on('change', function(values, handle) {
                    self.model.min_salary = moneyFormatter.from(values[0]);
                    self.model.max_salary = moneyFormatter.from(values[1]);
                });
        
                $('#salary-slider').find('div.noUi-value:nth-child(30)').html('£150,000+');
            },
        });

        let data = {
            model: {!! $advert !!},
            url: '{{ $edit ? route('advert.update', ['advert' => $advert]) : route('advert.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        @if($advert->status == \App\AdvertStatus::Draft)
            data.model.save_for_later = true;
        @else
            data.model.save_for_later = false;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" />
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