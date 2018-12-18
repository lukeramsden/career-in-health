@extends('layouts.app', ['title' => 'Create Your CV'])
@section('content')
    <cv-builder />
@endsection
{{--@section('script')--}}
    {{-- VUE TEMPLATES --}}
    {{----}}
    {{--@verbatim--}}
        {{--<script type="text/x-template" id="template__cv_builder">--}}
            {{--<div class="row">--}}
                {{--<div class="col-sm-12 col-md-8 col-xl-9">--}}
                    {{--<template v-for="(schema, index) in schemas">--}}
                        {{--<cv-section-multiple v-if="schema.multiple" :schema="schema" :model="model[schema.name]"></cv-section-multiple>--}}
                        {{--<cv-section-single v-else :schema="schema" :model="model[schema.name]"></cv-section-single>--}}
                    {{--</template>--}}
                {{--</div>--}}
                {{--<div class="col-sm-12 col-md-4 col-xl-3 order-first order-md-last mb-4">--}}
                    {{--<div class="btn-group-vertical">--}}
                        {{--@endverbatim--}}
                        {{--@onboarding--}}
                        {{--<a--}}
                        {{--href="{{ route('employee.next-onboard-step') }}"--}}
                        {{--class="btn btn-action disabled" id="next-step">Next Step</a>--}}
                        {{--@endonboarding--}}
                        {{--@verbatim--}}
                            {{--<button class="btn btn-primary" @click="show">View CV</button>--}}
                        {{--@endverbatim--}}
                        {{--<a href="{{ route('cv.pdf.download') }}" target="_blank" class="btn btn-primary">Download CV</a>--}}
                        {{--@verbatim--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</script>--}}

        {{--<script type="text/x-template" id="template__cv_section_single">--}}
            {{--<div class="card card-custom mb-3">--}}
                {{--<div class="card-body">--}}
                    {{--<div class="row align-content-center">--}}
                        {{--<div class="col-12">--}}
                            {{--<h4 class="d-inline-block">{{ schema.label }}</h4>--}}
                            {{--<p v-if="schema.sublabel" class="d-inline-block text-muted" style="font-size: 14px;">{{ schema.sublabel }}</p>--}}
                        {{--</div>--}}
                        {{--<div class="col-12">--}}
                            {{--<cv-item :multiple="false" :model="model" :schema="schema"></cv-item>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</script>--}}
        {{----}}
        {{--<script type="text/x-template" id="template__cv_section_multiple">--}}
            {{--<div class="card card-custom mb-3">--}}
                {{--<div class="card-body">--}}
                    {{--<div class="row align-content-center">--}}
                        {{--<div class="col-12">--}}
                            {{--<h4 class="d-inline-block">{{ schema.label }}</h4>--}}
                            {{--<p v-if="schema.sublabel" class="d-inline-block text-muted" style="font-size: 14px;">{{ schema.sublabel }}</p>--}}
                            {{--<button @click="add" class="btn btn-link float-right"><span class="oi oi-plus"></span></button>--}}
                        {{--</div>--}}
                        {{--<div class="col-12">--}}
                            {{--<template v-for="(model, index) in model">--}}
                                {{--<cv-item :multiple="true" :model="model" :schema="schema" :index="index" v-on:delete-cv-item="del"></cv-item>--}}
                            {{--</template>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</script>--}}
        {{----}}
        {{--<script type="text/x-template" id="template__cv_item">--}}
            {{--<div class="cv-item">--}}
                {{--<template v-if="model.editing">--}}
                    {{--<form @submit.prevent="save">--}}
                        {{--<div v-for="(field, index) in schema.fields" :key="fieldId(field)">--}}
                            {{--<template v-if="!_.isFunction(_.get(field, 'if')) || field.if(field, model)">--}}
                                {{--<template v-if="_.get(field, 'type') === 'input'">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<template v-if="_.get(field, 'label')">--}}
                                            {{--<label--}}
                                            {{--:for="fieldId(field)">--}}
                                                {{--{{ field.label }}--}}
                                            {{--</label>--}}
                                        {{--</template>--}}
                                        {{--<template v-if="_.get(field, 'inputType') === 'text'">--}}
                                            {{--<input--}}
                                            {{--type="text"--}}
                                            {{--class="form-control"--}}
                                            {{--v-model="model[field.model]"--}}
                                            {{--:id="fieldId(field)"--}}
                                            {{--:name="field.model"--}}
                                            {{--:maxlength="field.max"--}}
                                            {{--:aria-describedby="fieldId(field) + 'HelpBlock'"--}}
                                            {{--:required="field.required">--}}
                                {{----}}
                                            {{--<template v-if="_.get(field, 'helpText')">--}}
                                                {{--<small--}}
                                                {{--class="form-text text-muted"--}}
                                                {{--:id="fieldId(field) + 'HelpBlock'">--}}
                                                    {{--{{ field.helpText }}--}}
                                                {{--</small>--}}
                                            {{--</template>--}}
                                        {{--</template>--}}
                                        {{--<template v-else-if="_.get(field, 'inputType') === 'area'">--}}
                                            {{--<textarea--}}
                                            {{--class="form-control"--}}
                                            {{--cols="30"--}}
                                            {{--rows="10"--}}
                                            {{--v-model="model[field.model]"--}}
                                            {{--:id="fieldId(field)"--}}
                                            {{--:name="field.model"--}}
                                            {{--:maxlength="field.max"--}}
                                            {{--:placeholder="_.get(field, 'helpText')"--}}
                                            {{--:required="field.required">{{ model[field.model] }}</textarea>--}}
                                        {{--</template>--}}
                                    {{--</div>--}}
                                {{--</template>--}}
                                {{--<template v-else-if="_.get(field, 'type') === 'datePicker'">--}}
                                    {{--<template v-if="_.get(field, 'multiple')">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col" v-for="(field, index) in field.models" :key="fieldId(field)">--}}
                                                    {{--<date-picker--}}
                                                    {{--:id="fieldId(field)"--}}
                                                    {{--:schema="field"--}}
                                                    {{--@update-date="updateDate"--}}
                                                    {{--v-once></date-picker>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</template>--}}
                                    {{--<template v-else>--}}
                                        {{--<date-picker--}}
                                        {{--:id="fieldId(field)"--}}
                                        {{--:schema="field"--}}
                                        {{--@update-date="updateDate"--}}
                                        {{--v-once></date-picker>--}}
                                    {{--</template>--}}
                                {{--</template>--}}
                                {{--<template v-else-if="_.get(field, 'type') === 'checkbox'">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<div class="form-check">--}}
                                            {{--<input--}}
                                            {{--class="form-check-input"--}}
                                            {{--type="checkbox"--}}
                                            {{--:id="fieldId(field)"--}}
                                            {{--:name="field.model"--}}
                                            {{--v-model="model[field.model]">--}}
                                            {{--<label--}}
                                            {{--class="form-check-label"--}}
                                            {{--v-if="_.get(field, 'label')"--}}
                                            {{--:for="fieldId(field)">--}}
                                                {{--{{ field.label }}--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</template>--}}
                                {{--<template v-else-if="_.get(field, 'type') === 'dropdown'">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label v-if="field.label" :for="fieldId(field)">{{ field.label }}</label>--}}
                                        {{--<template v-if="_.get(field, 'handler') === 'select2'">--}}
                                            {{--<select2--}}
                                            {{--:id="fieldId(field)"--}}
                                            {{--v-model="model[field.model]">--}}
                                                {{--<option :value="null">-</option>--}}
                                                {{--<option v-for="item in field.data" :value="item.value">{{ item.name }}</option>--}}
                                            {{--</select2>--}}
                                        {{--</template>--}}
                                        {{--<template v-else>--}}
                                            {{--<select class="custom-select" :id="fieldId(field)" v-model="model[field.model]">--}}
                                                {{--<option :value="null">-</option>--}}
                                                {{--<option v-for="item in field.data" :value="item.value">{{ item.name }}</option>--}}
                                            {{--</select>--}}
                                        {{--</template>--}}
                                    {{--</div>--}}
                                {{--</template>--}}
                                {{--<template v-else-if="_.get(field, 'type') === 'file'">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<file-upload--}}
                                        {{--:id="fieldId(field)"--}}
                                        {{--:label="field.label"--}}
                                        {{--:helpText="field.helpText"--}}
                                        {{--:required="field.required"--}}
                                        {{--:max="field.max"--}}
                                        {{--:accept="field.fileTypes"--}}
                                        {{--v-model="model[field.model]" />--}}
                                    {{--</div>--}}
                                {{--</template>--}}
                            {{--</template>--}}
                        {{--</div>--}}
                    {{----}}
                        {{--<button :type="loading ? 'button' : 'submit'" class="btn btn-action w-25">--}}
                            {{--<loading-icon v-if="loading" />--}}
                            {{--<template v-else>Save</template>--}}
                        {{--</button>--}}
                        {{--<button type="button" class="btn btn-link" @click="cancel">Cancel</button>--}}
                    {{--</form>--}}
                {{--</template>--}}
                {{--<template v-else>--}}
                    {{--<div class="cv-item-inner">--}}
                        {{--<!-- PREFERENCES -->--}}
                        {{--<template v-if="schema.name === 'preferences'">--}}
                            {{--<template v-if="model.job_role">--}}
                                {{--<small><b>Job Role</b></small>--}}
                                {{--<p class="my-1">{{ schema.fields[0].data[model.job_role-1].name }}</p>--}}
                            {{--</template>--}}
                            {{--<template v-if="model.setting">--}}
                                {{--<small><b>Job Setting</b></small>--}}
                                {{--<p class="my-1">{{ schema.fields[1].data[model.setting-1].name }}</p>--}}
                            {{--</template>--}}
                            {{--<template v-if="model.type">--}}
                                {{--<small><b>Job Type</b></small>--}}
                                {{--<p class="my-1">{{ schema.fields[2].data[model.type-1].name }}</p>--}}
                            {{--</template>--}}
                            {{--<small><b>Relocation</b></small>--}}
                            {{--<p class="my-1">--}}
                                {{--<template v-if="model.willing_to_relocate">Willing to relocate</template>--}}
                                {{--<template v-else>Not willing to relocate</template>--}}
                            {{--</p>--}}
                        {{--</template>--}}
                        {{--<!-- EDUCATION -->--}}
                        {{--<template v-if="schema.name === 'education'">--}}
                            {{--<p class="my-1">{{ model.degree }} in {{ model.field_of_study }}</p>--}}
                            {{--<p class="my-1">{{ model.school_name }} - {{ model.location }}</p>--}}
                            {{--<template v-if="validDate(model.end_date)">--}}
                                {{--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                            {{--<template v-else>--}}
                                {{--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                        {{--</template>--}}
                        {{--<!-- WORK EXPERIENCE -->--}}
                        {{--<template v-if="schema.name === 'work_experience'">--}}
                            {{--<p class="my-1">{{ model.job_title }} at {{ model.company_name }}</p>--}}
                            {{--<p class="my-1">{{ model.location }}</p>--}}
                            {{--<template v-if="validDate(model.end_date)">--}}
                                {{--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                            {{--<template v-else>--}}
                                {{--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                            {{--<p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>--}}
                        {{--</template>--}}
                        {{--<!-- CERTIFICATIONS / LICENSES -->--}}
                        {{--<template v-if="schema.name === 'certifications'">--}}
                            {{--<p class="my-1">{{ model.title }}</p>--}}
                            {{--<template v-if="validDate(model.end_date)">--}}
                                {{--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                            {{--<template v-else>--}}
                                {{--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>--}}
                            {{--</template>--}}
                            {{--<p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>--}}
                            {{--<a :href="model.url" class="btn btn-link">View File</a>--}}
                        {{--</template>--}}
                    {{--</div>--}}
                    {{--<button v-if="multiple" class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)"><span class="oi oi-delete"></span></button>--}}
                    {{--<button class="btn btn-link btn-sm float-right" @click="edit"><span class="oi oi-pencil"></span></button>--}}
                {{--</template>--}}
            {{--</div>--}}
        {{--</script>--}}
        {{----}}
        {{--<script type="text/x-template" id="template__date-picker">--}}
            {{--<div>--}}
                {{--<template v-if="_.get(schema, 'inline')">--}}
                    {{--<div></div>--}}
                {{--</template>--}}
                {{--<template v-else>--}}
                    {{--<input type="text" class="form-control" :required="_.get(schema, 'required')">--}}
                {{--</template>--}}
                {{--<template v-if="_.get(schema, 'label')">--}}
                    {{--<small class="form-text text-muted">{{ schema.label }}</small>--}}
                {{--</template>--}}
            {{--</div>--}}
        {{--</script>--}}
        {{----}}
        {{--<script type="text/x-template" id="template__select2">--}}
          {{--<select>--}}
            {{--<slot></slot>--}}
          {{--</select>--}}
        {{--</script>--}}

        {{--<script type="text/x-template" id="template__file_upload">--}}
            {{--<div>--}}
                {{--<label v-if="label">{{ label }}</label>--}}
                {{--<div class="custom-file">--}}
                    {{--<input--}}
                    {{--:id="id"--}}
                    {{--:accept="mimeTypes()"--}}
                    {{--:required="required"--}}
                    {{--class="custom-file-input"--}}
                    {{--type="file"--}}
                    {{--ref="file"--}}
                    {{--@change="change()" />--}}
                {{----}}
                    {{--<label class="custom-file-label" v-if="label" :for="id">--}}
                        {{--<template v-if="file">{{ file.name }}</template>--}}
                        {{--<template v-else>Choose file...</template>--}}
                    {{--</label>--}}
                {{--</div>--}}
                {{----}}
                {{--<small v-if="helpText" class="text-muted">{{ helpText }}</small>--}}
            {{--</div>--}}
        {{--</script>--}}
    {{--@endverbatim--}}
    {{----}}
    {{--<script>--}}
        {{--Vue.component('cv-builder', {--}}
            {{--template: '#template__cv_builder',--}}
            {{--props: ['schemas', 'model'],--}}
            {{--methods: {--}}
                {{--show() {--}}
                    {{--lity('{{ route('cv.pdf.view', ['embed' => true]) }}');--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
        {{----}}
        {{--Vue.component('cv-section-single', {--}}
            {{--template: '#template__cv_section_single',--}}
            {{--props: ['schema', 'model'],--}}
        {{--});--}}
        {{----}}
        {{--Vue.component('cv-section-multiple', {--}}
            {{--template: '#template__cv_section_multiple',--}}
            {{--props: ['schema', 'model'],--}}
            {{--methods: {--}}
                {{--del (i) {--}}
                    {{--if(!confirm('Are you sure?')) return;--}}
                    {{--let modelId = _.get(this, ['model', i, 'id']);--}}
                    {{----}}
                    {{--// deleting real--}}
                    {{--if(!!modelId) {--}}
                        {{--axios.delete(_.get(this, 'schema.url') + '/' + modelId)--}}
                            {{--.then((response) => {--}}
                                {{--if(response.data.success)--}}
                                    {{--this.model.splice(i, 1);--}}
                            {{--})--}}
                            {{--.catch((error) => {--}}
                                {{--console.log(error);--}}
                                {{--_.forIn(--}}
                                    {{--error.response.data.errors,--}}
                                    {{--(errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))--}}
                                {{--);--}}
                            {{--});--}}
                    {{--} else this.model.splice(i, 1); // deleting create form--}}
                {{--},--}}
                {{--add: _.debounce(function() { this.model.push({ editing: true, new: true }) }, 500, { leading: true, trailing: false }),--}}
            {{--},--}}
        {{--});--}}
        {{----}}
        {{--Vue.component('cv-item', {--}}
            {{--template: '#template__cv_item',--}}
            {{--props: ['schema', 'model', 'index', 'multiple'],--}}
            {{--data () {--}}
                {{--return {--}}
                    {{--loading: false,--}}
                {{--}--}}
            {{--},--}}
            {{--methods: {--}}
                {{--//--}}
                {{--save () {--}}
                    {{--const self = this;--}}
                    {{--self.loading =  true;--}}
                    {{----}}
                    {{--if(!_.get(this, 'schema.url', false)) {--}}
                        {{--setTimeout(() => {--}}
                            {{--self.$set(self.model, 'editing', false);--}}
                            {{--self.loading =  false;--}}
                        {{--}, 500);--}}
                        {{--return;--}}
                    {{--}--}}
                    {{----}}
                    {{--// construct schema--}}
                    {{--const fields = _--}}
                        {{--// start chain--}}
                        {{--.chain(this.schema.fields)--}}
                        {{--// get array of all "models" fields--}}
                        {{--.flatMap('models')--}}
                        {{--// add original to prev array--}}
                        {{--.concat(this.schema.fields)--}}
                        {{--.thru((fields) => {--}}
                            {{--let mappedFields = _.map(fields, (field) => {--}}
                                {{--if(!field || !_.isFunction(field.if))--}}
                                    {{--return field;--}}
                                {{----}}
                                {{--if(field.if(field, self.model))--}}
                                    {{--return field;--}}
                            {{--});--}}
                            {{----}}
                            {{--return mappedFields;--}}
                        {{--});--}}
                        {{----}}
                    {{--const models = fields--}}
                        {{--// get all "model" fields--}}
                        {{--.flatMap('model')--}}
                        {{--// remove empty vals--}}
                        {{--.compact()--}}
                        {{--// end--}}
                        {{--.value();--}}
                    {{----}}
                    {{--const inputTypes = fields--}}
                        {{--// get all "model" fields--}}
                        {{--.flatMap('type')--}}
                        {{--// remove empty vals--}}
                        {{--.compact()--}}
                        {{--// remove duplicate values--}}
                        {{--.uniq()--}}
                        {{--// end--}}
                        {{--.value();--}}
                    {{----}}
                    {{--const multiple      = _.get(this, 'multiple');--}}
                    {{--const hasFileInput  = inputTypes.includes('file');--}}
                    {{----}}
                    {{--let options = {};--}}
                    {{----}}
                    {{--if(hasFileInput) {--}}
                        {{--const files = fields--}}
                            {{--.flatMap((el) => _.get(el, 'type') === 'file' ? [el] : [])--}}
                            {{--.compact()--}}
                            {{--.value();--}}
                            {{----}}
                        {{--_.set(options, ['headers', 'Content-Type'], 'multipart/form-data');--}}
                        {{--options.data = new FormData();--}}
                        {{----}}
                        {{--for(file in files)--}}
                            {{--options.data.append(file.model, this.model[file.model]);--}}

                        {{--/**--}}
                         {{--* Iterates all models, and appends them to options.data--}}
                         {{--* The catch is that value is run through JSON.stringify if it is an array--}}
                         {{--*/--}}
                        {{--// iterate models--}}
                        {{--_.forIn(--}}
                            {{--// only get model keys that are in schema--}}
                            {{--_.pick(this.model, models),--}}
                            {{--// run "func" with "main args" after running "main args" through "transform"--}}
                            {{--// key is left intact, v is : stringified if array, else left intact--}}
                            {{--(v, k) => _.overArgs(--}}
                                {{--// func--}}
                                {{--(k, v) => options.data.append(k, v),--}}
                                {{--// transform--}}
                                {{--[--}}
                                    {{--k => k, // stays the same--}}
                                    {{--v => _.isArray(v) ? JSON.stringify(v) : v, // JSON.stringify all arrays--}}
                                {{--])(k, v) // main args--}}
                        {{--);--}}
                    {{--} else {--}}
                        {{--// map internal model to schema--}}
                        {{--options.data = _.pick(this.model, models);--}}
                    {{--}--}}
                    {{----}}
                    {{--if(multiple) {--}}
                        {{--// get model id--}}
                        {{--let modelId = _.get(this, 'model.id', null);--}}
                        {{----}}
                        {{--options.url = !!modelId ?--}}
                              {{--_.get(this, 'schema.url') + '/' + modelId // multiple & updating existing--}}
                            {{--: _.get(this, 'schema.url');                // multiple & creating new--}}
                        {{----}}
                        {{--options.method = !!modelId ?--}}
                                  {{--'put'  // multiple & updating existing--}}
                                {{--: 'post' // multiple & creating new--}}
                        {{----}}
                    {{--} else {--}}
                        {{--options.method = 'put';--}}
                        {{--options.url = _.get(this, 'schema.url');--}}
                    {{--}--}}
                    {{----}}
                    {{--axios(options)--}}
                        {{--.then((response) => {--}}
                            {{--if(response.data.success) {--}}
                                {{--self.$set(self.model, 'editing', false);--}}
                                {{----}}
                                {{--self.$set(self.model, 'id', _.get(response, 'data.model.id'));--}}
                            {{--}--}}
                        {{----}}
                            {{--self.loading =  false;--}}
                            {{--self.model.new = false;--}}
                            {{--$('#next-step').removeClass('disabled');--}}
                        {{--})--}}
                        {{--.catch((error) => {--}}
                            {{--console.log(error);--}}
                            {{--_.forIn(--}}
                                {{--error.response.data.errors,--}}
                                {{--(errors, field) => errors.forEach((error) => toastr.error(error, changeCase.titleCase(field)))--}}
                            {{--);--}}
                            {{--self.loading =  false;--}}
                        {{--});--}}
                {{--},--}}
                {{--//--}}
                {{--cancel () {--}}
                    {{--if(_.get(this, 'multiple')) {--}}
                        {{--// all model keys from schema--}}
                        {{--let fields = _.map(this.schema.fields, 'model');--}}
                        {{--// all fields that are required--}}
                        {{--let requiredFields = _.filter(this.schema.fields, ['required', true]);--}}
                        {{--// get model with only fields that are in schema--}}
                        {{--// this mean we can store metadata for the form in the model--}}
                        {{--let model = _.pick(this.model, fields);--}}
                        {{----}}
                        {{--// if less keys in model than required fields--}}
                        {{--// delete item--}}
                        {{--if(_.keys(model).length < requiredFields.length)--}}
                            {{--this.$emit('delete-cv-item', this.index);--}}
                    {{--}--}}
                    {{----}}
                    {{--// invert editing--}}
                    {{--this.loading =  false;--}}
                    {{--this.$set(this.model, 'editing', false);--}}
                {{--},--}}
                {{--//--}}
                {{--edit () { this.$set(this.model, 'editing', true) },--}}
                {{--// turn field model in to pretty Id--}}
                {{--fieldId: field => _.camelCase('input_' + field.model),--}}
                {{--// event ($emit) handler for datepicker value changing--}}
                {{--updateDate (date, model) {--}}
                    {{--this.$set(this.model,--}}
                        {{--model,--}}
                        {{--!date ? undefined : moment(date || '').format('Y-MM-DD'));--}}
                {{--},--}}
                {{--// pretty-print date with momentjs--}}
                {{--formatDate: (date, format) => moment(date || '').format(format),--}}
                {{--validDate: (date) => moment(date || '')._isValid,--}}
            {{--},--}}
        {{--});--}}
        {{----}}
        {{--Vue.component('date-picker', {--}}
            {{--template: '#template__date-picker',--}}
            {{--props: ['schema'],--}}
            {{--mounted: function() {--}}
                {{--var self = this;--}}
                {{--let name = _.get(self, 'schema.model');--}}
                {{--$(self.$el.children[0])--}}
                    {{--.datepicker(_.get(self, 'schema.options', {}))--}}
                    {{--.datepicker('setDate', moment(self.$parent.model[name] || '').toDate())--}}
                    {{--.on('changeDate', (e) => {--}}
                        {{--self.$emit('update-date', e.date, name);--}}
                    {{--});--}}
            {{--},--}}
            {{--beforeDestroy: () => $(this.$el).datepicker('hide').datepicker('destroy'),--}}
        {{--});--}}
        {{----}}
        {{--Vue.component('file-upload', {--}}
            {{--template: '#template__file_upload',--}}
            {{--props: ['label', 'id', 'accept', 'helpText', 'required', 'max'],--}}
            {{--data() {--}}
                {{--return {--}}
                    {{--file: null--}}
                {{--}--}}
            {{--},--}}
            {{--methods: {--}}
                {{--change() {--}}
                    {{--let file = this.$refs.file.files[0];--}}
                    {{----}}
                    {{--if(filesize(file.size).to('KB') > this.max) {--}}
                        {{--toastr.error('File too big!');--}}
                        {{--return;--}}
                    {{--}--}}
                    {{----}}
                    {{--this.$emit('input', file);--}}
                    {{--this.file = file;--}}
                {{--},--}}
                {{--mimeTypes() {--}}
                    {{--return _.map(this.accept, (el) => {--}}
                       {{--return mime.getType(el);--}}
                    {{--});--}}
                {{--},--}}
            {{--},--}}
        {{--});--}}
        {{----}}
        {{--const schemas = [--}}
            {{--{--}}
                {{--name: 'preferences',--}}
                {{--label: 'Desired Job',--}}
                {{--sublabel: 'Help us match you with your next job',--}}
                {{--url: '{{ route('cv.preferences.update') }}',--}}
                {{--multiple: false,--}}
                {{--fields: [--}}
                    {{--{--}}
                        {{--type: 'dropdown',--}}
                        {{--handler: 'select2',--}}
                        {{--label: 'Job Role',--}}
                        {{--model: 'job_role',--}}
                        {{--data: [--}}
                            {{--@foreach(\App\JobRole::all() ?? [] as $job)--}}
                                {{--{--}}
                                    {{--name: '{{ $job->name }}',--}}
                                    {{--value: '{{ $job->id }}',--}}
                                {{--},--}}
                            {{--@endforeach--}}
                        {{--],--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'dropdown',--}}
                        {{--handler: 'select2',--}}
                        {{--label: 'Setting',--}}
                        {{--model: 'setting',--}}
                        {{--data: [--}}
                            {{--@foreach(\App\JobListing::$settings ?? [] as $id => $setting)--}}
                            {{--{--}}
                                {{--name: '{{ $setting }}',--}}
                                {{--value: '{{ $id }}',--}}
                            {{--},--}}
                            {{--@endforeach--}}
                        {{--]--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'dropdown',--}}
                        {{--handler: 'select2',--}}
                        {{--label: 'Type',--}}
                        {{--model: 'type',--}}
                        {{--data: [--}}
                            {{--@foreach(\App\JobListing::$types ?? [] as $id => $type)--}}
                            {{--{--}}
                                {{--name: '{{ $type }}',--}}
                                {{--value: '{{ $id }}',--}}
                            {{--},--}}
                            {{--@endforeach--}}
                        {{--]--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'checkbox',--}}
                        {{--label: 'I am willing to relocate',--}}
                        {{--model: 'willing_to_relocate',--}}
                    {{--},--}}
                {{--],--}}
            {{--},--}}
            {{--{--}}
                {{--name: 'education',--}}
                {{--label: 'Education',--}}
                {{--url: '{{ route('cv.education.store') }}',--}}
                {{--multiple: true,--}}
                {{--fields: [--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'Degree',--}}
                        {{--model: 'degree',--}}
                        {{--helpText: 'e.g. Diploma, Bachelor\'s, PhD.',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'College or University',--}}
                        {{--model: 'school_name',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'Field Of Study',--}}
                        {{--model: 'field_of_study',--}}
                        {{--helpText: 'e.g. Management, Nursing, Psychology.',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'City',--}}
                        {{--model: 'location',--}}
                        {{--helpText: 'e.g. London, Manchester, Birmingham.',--}}
                        {{--max: 150,--}}
                        {{--required: true,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'datePicker',--}}
                        {{--multiple: true,--}}
                        {{--models: [--}}
                            {{--{--}}
                                {{--model: 'start_date',--}}
                                {{--label: 'Start Date',--}}
                                {{--required: true,--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                {{--},--}}
                            {{--},--}}
                            {{--{--}}
                                {{--model: 'end_date',--}}
                                {{--label: 'End Date (or expected graduation date)',--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                    {{--clearBtn: true,--}}
                                {{--},--}}
                            {{--},--}}
                        {{--],--}}
                    {{--},--}}
                {{--],--}}
            {{--},--}}
            {{--{--}}
                {{--name: 'work_experience',--}}
                {{--label: 'Work Experience',--}}
                {{--url: '{{ route('cv.workExperience.store') }}',--}}
                {{--multiple: true,--}}
                {{--fields: [--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'Job Title',--}}
                        {{--model: 'job_title',--}}
                        {{--helpText: 'e.g. Manager, Senior Nurse, Midwife.',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'Company Name',--}}
                        {{--model: 'company_name',--}}
                        {{--helpText: 'Name of the company.',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'area',--}}
                        {{--label: 'Description',--}}
                        {{--model: 'description',--}}
                        {{--helpText: 'A small description of your time here.',--}}
                        {{--required: false,--}}
                        {{--max: 500,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'City',--}}
                        {{--model: 'location',--}}
                        {{--helpText: 'e.g. London, Manchester, Birmingham.',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'datePicker',--}}
                        {{--multiple: true,--}}
                        {{--models: [--}}
                            {{--{--}}
                                {{--model: 'start_date',--}}
                                {{--label: 'Start Date',--}}
                                {{--required: true,--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                {{--},--}}
                            {{--},--}}
                            {{--{--}}
                                {{--model: 'end_date',--}}
                                {{--label: 'End Date (leave empty if you still work here)',--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                    {{--clearBtn: true,--}}
                                {{--},--}}
                            {{--},--}}
                        {{--],--}}
                    {{--},--}}
                {{--],--}}
            {{--},--}}
            {{--{--}}
                {{--name: 'certifications',--}}
                {{--label: 'Certifications and Licenses',--}}
                {{--url: '{{ route('cv.certifications.store') }}',--}}
                {{--multiple: true,--}}
                {{--fields: [--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'text',--}}
                        {{--label: 'Title',--}}
                        {{--model: 'title',--}}
                        {{--required: true,--}}
                        {{--max: 150,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'datePicker',--}}
                        {{--multiple: true,--}}
                        {{--models: [--}}
                            {{--{--}}
                                {{--model: 'start_date',--}}
                                {{--label: 'Start Date',--}}
                                {{--required: true,--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                {{--},--}}
                            {{--},--}}
                            {{--{--}}
                                {{--model: 'end_date',--}}
                                {{--label: 'Expiration Date (leave empty if this certification or license does not expire)',--}}
                                {{--inline: false,--}}
                                {{--options: {--}}
                                    {{--format: 'MM yyyy',--}}
                                    {{--minViewMode: 1,--}}
                                    {{--maxViewMode: 2,--}}
                                    {{--clearBtn: true,--}}
                                {{--},--}}
                            {{--},--}}
                        {{--],--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'input',--}}
                        {{--inputType: 'area',--}}
                        {{--label: 'Description',--}}
                        {{--model: 'description',--}}
                        {{--required: false,--}}
                        {{--max: 500,--}}
                    {{--},--}}
                    {{--{--}}
                        {{--type: 'file',--}}
                        {{--model: 'file',--}}
                        {{--if: (field, model) => model.new || false,--}}
                        {{--label: 'PDF or Picture of Certification/License',--}}
                        {{--helpText: '(.pdf, .png, .jpg, .jpeg)',--}}
                        {{--required: true,--}}
                        {{--max: 1024,--}}
                        {{--fileTypes: ['pdf', 'png', 'jpg']--}}
                    {{--},--}}
                {{--]--}}
            {{--},--}}
        {{--];--}}
        {{----}}
    {{--</script>--}}
{{--@endsection--}}