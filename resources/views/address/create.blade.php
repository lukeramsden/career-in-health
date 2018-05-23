@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <address-form :model="model" :url="url" :create-new="createNew"></address-form>
    </div>
@endsection
@section('script')
    {{-- development version, includes helpful console warnings --}}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.js"></script>

    @verbatim
        <script type="text/x-template" id="template__address-form">
            <form @submit="submit" :action="url" method="post" >
                @endverbatim
                    {{ csrf_field() }}
                @verbatim
                
                <div class="card-columns" id="address-edit-card-columns">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="model.name" name="name" maxlength="120" required>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control my-1" v-model="model.address_line_1" name="address_line_1" placeholder="Line 1" maxlength="60" required>
                                <input type="text" class="form-control my-1" v-model="model.address_line_2" name="address_line_2" placeholder="Line 2" maxlength="60">
                                <input type="text" class="form-control my-1" v-model="model.address_line_3" name="address_line_3" placeholder="Line 3" maxlength="60">
                            </div>
                            
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="awesomplete form-control" v-model="model.county" list="counties" name="county" placeholder="County" maxlength="60" required />
                                    <datalist id="counties">
                                        @endverbatim
                                            @foreach(\App\Location::getCounties() as $county)
                                                <option value="{{ $county }}">{{ $county }}</option>
                                            @endforeach
                                        @verbatim
                                    </datalist>
                                    <input type="text" class="form-control" v-model="model.postcode" name="postcode" placeholder="Postcode" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @endverbatim
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Location</label>
                                <select2 v-model="model.location_id" name="location_id" required>
                                    <option :value="null">-</option>
                                    @foreach(\App\Location::getAllLocations() as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select2>
                            </div>
                        </div>
                    </div>
                    @verbatim
                    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <button type="submit" class="btn btn-action btn-block">{{ createNew ? 'Create' : 'Save' }}</button>
                            
                                @endverbatim
                                @if($edit)
                                    <a href="{{ route('address.destroy', [$address]) }}" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-block">Delete</a>
                                @endif
                                @verbatim
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
        
        Vue.component('address-form', {
            template: '#template__address-form',
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
                            if(response.status === 200) {
                                toastr.success('Updated!')
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
            computed: {},
            watch: {},
            data() {
                return {
                    {{--locations: {!! \App\Location::all()->toJson() !!}--}}
                };
            },
            mounted() {},
        });

        let data = {
            model: {!! $edit ? $address : '{}' !!},
            url: '{{ $edit ? route('address.update', ['address' => $address]) : route('address.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
            {{-- TODO: old: {{ json_encode(Session::getOldInput()) }},--}}
        };
        
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.css" />
    
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
        
        .awesomplete {
            display: block;
        }
    </style>
@endsection