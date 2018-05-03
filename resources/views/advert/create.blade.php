@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <advert-form :model="model" :url="url" :method="method" :create-new="createNew"></advert-form>
    </div>
@endsection
@section('script')
    {{-- development version, includes helpful console warnings --}}
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    @verbatim
        <script type="text/x-template" id="template__advert-form">
            <form @submit.prevent="submit">
                <div class="card-columns" id="advert-edit-card-columns">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" v-model="model.title" maxlength="120" required>
                            </div>
                        </div>
                    </div>
                    
                    <template v-if="!model.save_for_later">
                        <div class="card card-custom">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="model.description" class="form-control" rows="25" maxlength="3000"></textarea>
                                </div>
                            </div>
                        </div>
    
                        <div class="card card-custom">
                            <div class="card-body">
                                <select2 v-model="model.address_id">
                                    <option :value="null">-</option>
                                    <option v-for="address in addresses" :value="address.id">{{ address.name }}</option>
                                </select2>
                                @endverbatim
                                <p class="text-muted mb-0 mt-2">This will be the address that you want to find staff for. If you haven't created an address yet, <a class="text-action" href='{{ route('address.create') }}'>click here</a> to create one.</p>
                                @verbatim
                            </div>
                        </div>
                    </template>
                    
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="inputSaveForLater" v-model="model.save_for_later">
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
          <select>
            <slot></slot>
          </select>
        </script>
    @endverbatim
    
    <script>
        toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "positionClass": "toast-top-right",
            "progressBar": true,
      	};
        
        Vue.component('select2', {
          template: '#template__select2',
          props: ['options', 'value'],
          mounted: function () {
            var vm = this
            $(this.$el)
              // init select2
              .select2({
                  dropdownAutoWidth : true,
                  width: 'auto'
              })
              .val(this.value)
              .trigger('change')
              // emit event on change.
              .on('change', function () {
                vm.$emit('input', this.value)
              })
          },
          watch: {
            value: function (value) {
              // update value
              $(this.$el)
              	.val(value)
              	.trigger('change')
            },
            options: function (options) {
              // update options
              $(this.$el).empty().select2({ data: options })
            }
          },
          destroyed: function () {
            $(this.$el).off().select2('destroy')
          }
        })
        
        Vue.component('advert-form', {
            template: '#template__advert-form',
            props: ['model', 'url', 'method', 'createNew'],
            methods: {
                submit() {
                    console.log('submit()');
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
        });

        let data = {
            model: {!! $advert !!},
            url: '{{ $edit ? route('advert.update', ['advert' => $advert]) : route('advert.store') }}',
            method: '{{ $edit ? 'put' : 'post' }}',
            createNew: {{ $edit ? 'false' : 'true' }},
        };
        
        const app = new Vue({
            el: '#app',
            data: data,
        });
        
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
        
        .custom-checkbox .custom-control-label::before {
            border: 1px solid #495057;
        }
    </style>
@endsection