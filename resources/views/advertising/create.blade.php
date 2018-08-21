@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <h2 class="my-3"><em>Preview</em></h2>
        <a id="advert" :href="model.links_to" target="_blank">
            <template v-if="model.imagePreview && !model.removeImage">
                <div class="card-advert-image-preview">
                    <img :src="model.imagePreview">
                    <div>
                        <h5>@{{ model.title }}</h5>
                        <p>@{{ model.body }}</p>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="card-advert-text">
                    <div>
                        @{{ model.title }}
                    </div>
                    <div v-if="model.body">
                        <p>@{{ model.body }}</p>
                    </div>
                </div>
            </template>
        </a>
        <hr>
        <advert-form :model="model" :url="url" :create-new="createNew"></advert-form>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    @verbatim
        <script type="text/x-template" id="template__advert-form">
            <form @submit="submit" :action="url" method="post" enctype="multipart/form-data">
                @endverbatim
                    {{ csrf_field() }}
                @verbatim
                
                <div class="card-columns smaller-card-columns">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title (<span class='text-action'>*</span>)</label>
                                <input
                                type="text"
                                class="form-control"
                                v-model="model.title"
                                name="title"
                                maxlength="255"
                                required>
                            </div>
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Body</label>
                                <textarea
                                v-model="model.body"
                                class="form-control"
                                name="body"
                                rows="10"
                                maxlength="255"></textarea>
                            </div>
                        </div>
                    </div>
    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Click-through Link (<span class='text-action'>*</span>)</label>
                                <input
                                type="text"
                                class="form-control"
                                v-model="model.links_to"
                                name="links_to"
                                maxlength="500"
                                required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Background Image</label>
                                <input
                                type="file"
                                class="form-control-file"
                                v-on:change="imageChanged"
                                name="image"
                                accept="image/png, image/jpeg">
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="inputRemoveImage"
                                           value="1"
                                           name="removeImage"
                                           v-model="model.removeImage">
                                    <label class="custom-control-label"
                                           for="inputRemoveImage">Remove image?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @endverbatim
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Location</label>
                                <select2 v-model="model.location_id" name="location_id">
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
                        <div class="card-header">
                            <em>Target Audience</em>
                        </div>
                        <div class="card-body">
                            @endverbatim
                                <div class="form-group">
                                    <label>Location</label>
                                    <select2 v-model="model.dem_location_id" name="dem_location_id">
                                        <option :value="null">Any location</option>
                                        @foreach(\App\Location::getAllLocations() as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select2>
                                </div>
                            
                                <div class="form-group">
                                    <label>Job Role</label>
                                    <select2 v-model="model.dem_job_role_id" name="dem_job_role_id">
                                        <option :value="null">Any role</option>
                                        @foreach(\App\JobRole::all() as $job)
                                            <option value="{{ $job->id }}">{{ $job->name }}</option>
                                        @endforeach
                                    </select2>
                                </div>
                            @verbatim
        
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="inputDemWillRelocate"
                                           value="1"
                                           name="dem_will_relocate"
                                           v-model="model.dem_will_relocate">
                                    <label class="custom-control-label"
                                           for="inputDemWillRelocate">Must be able to relocate</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="inputSaveForLater" name="savingForLater" value="1" v-model="model.savingForLater">
                                    <label class="custom-control-label" for="inputSaveForLater">Save For Later?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-action btn-block">{{ createNew ? 'Create' : 'Save' }}</button>
                                
                                @endverbatim
                                @if($edit)
                                    <a href="{{ route('advertising.destroy', [$advert]) }}" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-block">Delete</a>
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
        
        Vue.component('advert-form', {
            template: '#template__advert-form',
            props: ['model', 'url', 'method', 'createNew'],
            methods: {
                submit(event) {
                    if(this.createNew) {
                        return true;
                    }
                    
                    let formData = new FormData();
                    let model = _.omit(this.model, ['imagePreview']);
                    console.log(model);
                    
                    for(k in model)
                    {
                        let v = model[k];
                        if(_.isBoolean(v))
                            formData.append(k, v ? '1' : '0');
                        else if(!_.isNull(v))
                            formData.append(k, v);
                    }
                    
                    axios({
                        url: this.url,
                        method: 'post',
                        data: formData,
                    })
                        .then((response) => {
                            if(response.data.success) {
                                toastr.success('Updated!');
                                if (_.get(response, 'data.model.active', false))
                                    toastr.info('This job-listing is now live.');
                                else
                                    toastr.info('This job-listing is not yet live.');
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
                imageChanged(e) {
                    let reader  = new FileReader();
                    const self = this;
                    reader.addEventListener('load', function () {
                        self.$set(self.model, 'imagePreview', reader.result);
                        self.$set(self.model, 'image', e.target.files[0]);
                    }, false);
                  
                    if (e.target.files[0]) {
                        reader.readAsDataURL(e.target.files[0]);
                    }
                },
            },
            computed: {},
            watch: {},
            data() {
                return {};
            },
            mounted() {},
        });

        let data = {
            model: {!! $edit ? $advert:json_encode(Session::getOldInput()) ?: '{}' !!},
            url: '{{ $edit ? route('advertising.update', [$advert]):route('advertising.store') }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        @if($advert->active)
            data.model.savingForLater = false;
        @else
            data.model.savingForLater = true;
        @endif
        
        @isset($advert->image_path)
            data.model.imagePreview = '{{ Storage::url($advert->image_path) }}';
        @endisset
        
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .select2 {
            display: block;
        }
        
        .select2-container--default .select2-selection--single {
            border-color: #ced4da;
        }
    </style>
@endsection