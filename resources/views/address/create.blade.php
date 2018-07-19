@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5" style="min-height: 70vh;">
        <div class="card-columns" id="address-edit-card-columns">
            <address-form :model="model" :url="url" :create-new="createNew"></address-form>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    
    @verbatim
        <script type="text/x-template" id="template__address-form">
            <form @submit="submit" :action="url" method="post" enctype="multipart/form-data">
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
                            <div class="input-group">
                                <input class="form-control" id="county-dropdown" v-model="model.county" list="counties" name="county" placeholder="County" maxlength="60" required />
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
                            <label>Image Gallery (20 max)</label>
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

        <script type="text/x-template" id="template__file-upload">
            <div class="dropzone"></div>
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
        
        Vue.component('file-upload', {
            template: '#template__file-upload',
            mounted() {
                if(!this.hasBeenMounted) {
                    const dropzoneOpts = {
                        // options
                        url: "/",
                        autoProcessQueue: false,
                        uploadMultiple: true,
                        parallelUploads: 20,
                        maxFiles: 20,
                        acceptedFiles: 'image/png,image/jpeg',
                        autoQueue: false,
                        addRemoveLinks: true,
                    };

                    this.dropzone = new Dropzone(this.$el, dropzoneOpts);
                    this.hasBeenMounted = true;
                }
                
                this.dropzone.enable();
                
                let vm = this;

                const emitUpdate = file => vm.$emit('update', vm.dropzone.getAcceptedFiles());
                this.dropzone.on('addedfiles', emitUpdate);
                this.dropzone.on('removedfile', emitUpdate);
            },
            beforeDestroy() {
                this.dropzone.disable();
            },
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
                            if(response.status === 200)
                                toastr.success('Updated!');
                            
                            console.log(response);
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
                fileUploadUpdate(files) {
                    this.model.images = files;
                    console.log(this.model);
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
            model: {!! $edit ? $address : json_encode(Session::getOldInput()) ?: '{}' !!},
            url: '{{ $edit ? route('address.update', ['address' => $address]) : route('address.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
        
        // Disable auto discover for all elements:
        Dropzone.autoDiscover = false;
        
        $(function() {
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
            
            let $county = $('#county-dropdown');
            const countyDropdown = new Awesomplete('#county-dropdown');
            
            $county.on('awesomplete-selectcomplete', function(event) {
                $county[0].dispatchEvent(new Event('input', { 'bubbles': true }));
                countyDropdown.close();
            });
        });
    </script>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.css" />
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
        
        .awesomplete {
            display: block;
        }
    </style>
@endsection