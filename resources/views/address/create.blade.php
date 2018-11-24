@extends('layouts.app', ['title' => 'Create An Address'])
@section('content')
    <create-address :address-id="{{ $edit ? $address->id : 'null' }}" />
    <div class="container mt-lg-5" style="min-height: 70vh;">
        <div class="card-columns smaller-card-columns">
            <address-form :model="model" :url="url" :create-new="createNew"></address-form>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    
    @verbatim
        <script type="text/x-template" id="template__address-form">
            <form @submit="submit"
                  :action="url"
                  method="post"
                  enctype="multipart/form-data">
                @endverbatim
                    {{ csrf_field() }}
                @verbatim
                
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name (<span class='text-action'>*</span>)</label>
                            <input type="text" class="form-control" v-model="model.name" name="name" maxlength="120" required>
                        </div>

                        <div class="form-group">
                            <label>Address (<span class='text-action'>*</span>)</label>
                            <input type="text" class="form-control my-1" v-model="model.address_line_1" name="address_line_1" placeholder="Line 1" maxlength="60" required>
                            <input type="text" class="form-control my-1" v-model="model.address_line_2" name="address_line_2" placeholder="Line 2" maxlength="60">
                            <input type="text" class="form-control my-1" v-model="model.address_line_3" name="address_line_3" placeholder="Line 3" maxlength="60">
                        </div>
                        
                        <div class="form-group">
                            <label>Postcode (<span class="text-action">*</span>)</label>
                            <input type="text" class="form-control my-1" v-model="model.postcode" name="postcode" placeholder="Post Code" maxlength="60">
                        </div>
                        
                        <div class="form-group">
                            <label>About</label>
                            <textarea name="about" class="form-control"
                                      placeholder="500 characters" rows="8"
                                      maxlength="500"
                                      v-model="model.about"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Contact Details</label>
                            <input
                                type="tel"
                                class="form-control my-1"
                                placeholder="Phone Number"
                                name="phone"
                                v-model="model.phone">
                                
                            <input
                                type="email"
                                class="form-control my-1"
                                placeholder="Email Address"
                                name="email"
                                v-model="model.email">
                        </div>
                    </div>
                </div>
                
                @endverbatim
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <label>City (<span class='text-action'>*</span>)</label>
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
                        <label>Image Gallery</label>
                        <file-upload @update="fileUploadUpdate"></file-upload>
                    </div>
                </div>
                    
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <button type="submit" class="btn btn-action btn-block">{{ createNew ? 'Create' : 'Save' }}</button>
                        
                            @endverbatim
                            @if($edit)
                                <a href="{{ route('address.destroy', [$address]) }}" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-block">Delete</a>
                                <a href="{{ route('address.show', [$address]) }}" class="btn btn-info btn-block">View Address</a>
                            @endif
                            @verbatim
                        </div>
                    </div>
                </div>
            </form>
        </script>
    @endverbatim

    <script>
        Vue.component('address-form', {
            template: '#template__address-form',
            props: ['model', 'url', 'method', 'createNew'],
            methods: {
                submit(event) {
                    let formData = new FormData();
                    for (let key in this.model) {
                        let val = this.model[key];
                        if (_.isArray(val)) {
                            for (arrKey in val) {
                                let arrVal = val[arrKey];
                                formData.append(key + '[]', arrVal);
                            }
                        } else {
                            formData.append(key, val || '');
                        }
                    }
                    if(this.createNew) {
                        axios.post(this.url, formData)
                            .then((response) => {
                                if (response.data.success)
                                {
                                    toastr.success('Created!');
                                    window.location = response.data.redirectTo;
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                                _.forIn(
                                    error.response.data.errors,
                                    (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                                );
                            });
                    } else {
                        axios.post(this.url, formData)
                            .then((response) => {
                                if (response.data.success)
                                    toastr.success('Updated!');
                            })
                            .catch((error) => {
                                console.log(error);
                                _.forIn(
                                    error.response.data.errors,
                                    (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                                );
                            });
                    }
                    
                    event.preventDefault();
                    return false;
                },
                fileUploadUpdate(files) {
                    this.$set(this.model, 'images', files);
                },
            },
            computed: {},
            watch: {},
            data() {
                return {
                    {{--locations: {!! \App\Location::all()->toJson(), !!}--}}
                };
            },
            mounted() {
            },
            destroyed() {
            },
        });

        let data = {
            model: {!! $edit?($address??'{}'):(Session::hasOldInput()?json_encode(Session::getOldInput()):'{}')!!},
            url: '{{ $edit ? route('address.update', ['address' => $address]) : route('address.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
        
        $(function() {
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        });
    </script>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" />
    
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