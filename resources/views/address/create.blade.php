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
                    });
            },
            watch: {
                value(value) {
                    // update value
                    $(this.$el)
                        .val(value)
                        .trigger('change');
                },
                options(options) {
                    // update options
                    $(this.$el).empty().select2({ data: options });
                },
            },
            destroyed() {
                $(this.$el).off().select2('destroy');
            },
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
                        maxFilesize: 10,
                        acceptedFiles: 'image/png,image/jpeg',
                        autoQueue: false,
                        addRemoveLinks: false,
                    };

                    this.dropzone = new Dropzone(this.$el, dropzoneOpts);
                    this.hasBeenMounted = true;
                    
                    this.dropzone.on('addedfile', function(file) {
                        // Create the remove button
                        let removeButton = Dropzone.createElement('<button class="btn btn-outline-danger btn-sm btn-block">Remove file</button>');
                    
                        // Capture the Dropzone instance as closure.
                        let self = this;
                    
                        // Listen to the click event
                        removeButton.addEventListener('click', function(e) {
                            // Make sure the button click doesn't submit the form:
                            e.preventDefault();
                            e.stopPropagation();
                    
                            // Remove the file preview.
                            self.removeFile(file);
                            
                            @if($edit)
                                // remove file on server
                                axios({
                                    url: route('media.destroy', {media: file.id}),
                                    method: 'post',
                                })
                                    .then((response) => { })
                                    .catch((error) => {
                                        console.log(error);
                                        _.forIn(
                                            error.response.data.errors,
                                            (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                                        );
                                    });
                            @endif
                        });
                    
                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                        
                        @if($edit)
                            if(!file.id) {
                                let formData = new FormData();
                                formData.append('image', file);
                                this.emit('sending', file, undefined, formData);
                                
                                axios.post(route('address.image.store', {address:{{$address->id}}}), formData, {
                                        // config
                                        onUploadProgress: progressEvent => {
                                            let percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
                                            self.emit('uploadprogress', file, percentCompleted, progressEvent.loaded)
                                            console.log(`progress: ${file.name} is ${percentCompleted}% (${progressEvent.loaded}/${progressEvent.total}) complete`);
                                        }
                                    })
                                    .then((response) => {
                                        file.id = _.get(response, 'data.model.id');
                                        self.emit('success', file, response);
                                        self.emit('complete', file);
                                    })
                                    .catch((error) => {
                                        console.log(error);
                                        self.emit('error', file, 'Error');
                                        _.forIn(
                                            error.response.data.errors,
                                            (errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))
                                        );
                                    });
                            }
                            
                        @else
                            this.emit('complete', file);
                        @endif
                    });
                    
                    @if($edit)
                        let files = [
                            @foreach($address->getMedia('images') as $image)
                                {
                                    id: {{ $image->id }},
                                    name: '{{ $image->name }}',
                                    size: {{ $image->size }},
                                    dataURL: '{{ $image->getFullUrl() }}',
                                },
                            @endforeach
                        ];
                        
                        // recursive IIFE used to ensure that files are added one-by-one
                        (function procFile(self) {
                            if(files.length < 1)
                                return;
                            
                            let file = files.shift();
                            
                            self.dropzone.files.push(file);
                            
                            // Call the default addedfile event handler
                            self.dropzone.emit('addedfile', file);
                            
                            self.dropzone.createThumbnailFromUrl(file,
                                self.dropzone.options.thumbnailWidth, self.dropzone.options.thumbnailHeight,
                                self.dropzone.options.thumbnailMethod, true, function (thumbnail) {
                                    self.dropzone.emit('thumbnail', file, thumbnail);
                                    // Make sure that there is no progress bar, etc...
                                    self.dropzone.emit('complete', file);
                                    
                                    self.dropzone.options.maxFiles = self.dropzone.options.maxFiles - 1;
                                    
                                    procFile(self);
                                });
                        })(this);
                    @endif
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
        
        // Disable auto discover for all elements:
        Dropzone.autoDiscover = false;
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
        
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