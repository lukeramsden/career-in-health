<template>
  <div class="card card-custom">
    <div class="card-body">
      <div v-if="loaded" class="row align-content-center">
        <div class="col-12">
          <h4 class="d-inline-block">Job Preferences</h4>
          <p class="sublabel d-inline-block text-muted">
            Help us match you with your next job
          </p>
        </div>
        <div class="col-12">
          <div class="cv-item">
            <template v-if="open">
              <form>
                <div class="form-group">
                  <label for="job_role">Job Role</label>
                  <select2
                    id="job_role"
                    v-model="preferences.job_role">
                    <option :value="null">-</option>
                    <option v-for="item in jobRoles" :key="item.id" :value="item.id">
                      {{ item.value }}
                    </option>
                  </select2>
                </div>
                <!--<div v-for="(field, idx) in schema.fields" :key="fieldId(field)">-->
                <!--<template v-if="!_.isFunction(_.get(field, 'if')) || field.if(field, model)">-->
                <!--<template v-if="_.get(field, 'type') === 'input'">-->
                <!--<div class="form-group">-->
                <!--<template v-if="_.get(field, 'label')">-->
                <!--<label-->
                <!--:for="fieldId(field)">-->
                <!--{{ field.label }}-->
                <!--</label>-->
                <!--</template>-->
                <!--<template v-if="_.get(field, 'inputType') === 'text'">-->
                <!--<input-->
                <!--type="text"-->
                <!--class="form-control"-->
                <!--v-model="model[field.model]"-->
                <!--:id="fieldId(field)"-->
                <!--:name="field.model"-->
                <!--:maxlength="field.max"-->
                <!--:aria-describedby="fieldId(field) + 'HelpBlock'"-->
                <!--:required="field.required">-->

                <!--<template v-if="_.get(field, 'helpText')">-->
                <!--<small-->
                <!--class="form-text text-muted"-->
                <!--:id="fieldId(field) + 'HelpBlock'">-->
                <!--{{ field.helpText }}-->
                <!--</small>-->
                <!--</template>-->
                <!--</template>-->
                <!--<template v-else-if="_.get(field, 'inputType') === 'area'">-->
                <!--<textarea-->
                <!--class="form-control"-->
                <!--cols="30"-->
                <!--rows="10"-->
                <!--v-model="model[field.model]"-->
                <!--:id="fieldId(field)"-->
                <!--:name="field.model"-->
                <!--:maxlength="field.max"-->
                <!--:placeholder="_.get(field, 'helpText')"-->
                <!--:required="field.required">{{ model[field.model] }}</textarea>-->
                <!--</template>-->
                <!--</div>-->
                <!--</template>-->
                <!--<template v-else-if="_.get(field, 'type') === 'datePicker'">-->
                <!--<template v-if="_.get(field, 'multiple')">-->
                <!--<div class="form-group">-->
                <!--<div class="row">-->
                <!--<div class="col" v-for="(field, index) in field.models" :key="fieldId(field)">-->
                <!--<date-picker-->
                <!--:id="fieldId(field)"-->
                <!--:schema="field"-->
                <!--@update-date="updateDate"-->
                <!--v-once></date-picker>-->
                <!--</div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</template>-->
                <!--<template v-else>-->
                <!--<date-picker-->
                <!--:id="fieldId(field)"-->
                <!--:schema="field"-->
                <!--@update-date="updateDate"-->
                <!--v-once></date-picker>-->
                <!--</template>-->
                <!--</template>-->
                <!--<template v-else-if="_.get(field, 'type') === 'checkbox'">-->
                <!--<div class="form-group">-->
                <!--<div class="form-check">-->
                <!--<input-->
                <!--class="form-check-input"-->
                <!--type="checkbox"-->
                <!--:id="fieldId(field)"-->
                <!--:name="field.model"-->
                <!--v-model="model[field.model]">-->
                <!--<label-->
                <!--class="form-check-label"-->
                <!--v-if="_.get(field, 'label')"-->
                <!--:for="fieldId(field)">-->
                <!--{{ field.label }}-->
                <!--</label>-->
                <!--</div>-->
                <!--</div>-->
                <!--</template>-->
                <!--<template v-else-if="_.get(field, 'type') === 'dropdown'">-->
                <!--<div class="form-group">-->
                <!--<label v-if="field.label" :for="fieldId(field)">{{ field.label }}</label>-->
                <!--<template v-if="_.get(field, 'handler') === 'select2'">-->
                <!--<select2-->
                <!--:id="fieldId(field)"-->
                <!--v-model="model[field.model]">-->
                <!--<option :value="null">-</option>-->
                <!--<option v-for="item in field.data" :value="item.value">{{ item.name }}</option>-->
                <!--</select2>-->
                <!--</template>-->
                <!--<template v-else>-->
                <!--<select class="custom-select" :id="fieldId(field)" v-model="model[field.model]">-->
                <!--<option :value="null">-</option>-->
                <!--<option v-for="item in field.data" :value="item.value">{{ item.name }}</option>-->
                <!--</select>-->
                <!--</template>-->
                <!--</div>-->
                <!--</template>-->
                <!--<template v-else-if="_.get(field, 'type') === 'file'">-->
                <!--<div class="form-group">-->
                <!--<file-upload-->
                <!--:id="fieldId(field)"-->
                <!--:label="field.label"-->
                <!--:helpText="field.helpText"-->
                <!--:required="field.required"-->
                <!--:max="field.max"-->
                <!--:accept="field.fileTypes"-->
                <!--v-model="model[field.model]" />-->
                <!--</div>-->
                <!--</template>-->
                <!--</template>-->
                <!--</div>-->

                <button type="button"
                        :class="{ 'disabled': saving }"
                        :disabled="saving"
                        class="btn btn-action w-25"
                        @click="save">
                  <loading-icon v-if="saving" />
                  <template v-else>Save</template>
                </button>
                <button type="button" class="btn btn-link" @click="cancel">Cancel</button>
              </form>
            </template>
            <template v-else>
              <div class="cv-item-inner">
                <template v-if="model.job_role">
                  <small><b>Job Role</b></small>
                  <p class="my-1">{{ jobRole }}</p>
                </template>
                <template v-if="model.setting">
                  <small><b>Job Setting</b></small>
                  <p class="my-1">{{ setting }}</p>
                </template>
                <template v-if="model.type">
                  <small><b>Job Type</b></small>
                  <p class="my-1">{{ type }}</p>
                </template>
                <small><b>Relocation</b></small>
                <p class="my-1">
                  <template v-if="model.willing_to_relocate">Willing to relocate</template>
                  <template v-else>Not willing to relocate</template>
                </p>
              </div>
              <button class="btn btn-link btn-sm float-right"
                      @click="open = true">
                <span class="oi oi-pencil"></span>
              </button>
            </template>
          </div>
        </div>
      </div>
      <loading-icon v-else />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    model: {
      type: Object,
      required: true,
    },
  },
  data()
  {
    return {
      open: false,
      preferences: {},
      original: {},

      loaded: false,
      saving: false,

      jobRoles: [],
      settings: [],
      types: [],
    };
  },
  mounted()
  {
    this.original    = _.cloneDeep( this.model );
    this.preferences = _.cloneDeep( this.model );

    this.load().then( () =>
    {
      this.loaded = true;
    } );
  },
  methods: {
    async load()
    {
      const [ jobRoles, settings, types ] = await Promise.all( [
        axios.get( route( 'get-all-job-roles' ) ),
        axios.get( route( 'get-all-listing-settings' ) ),
        axios.get( route( 'get-all-listing-types' ) ),
      ] );

      this.jobRoles = _.map( jobRoles.data, ( { id, name } ) => ( { id, value: name } ) );
      this.settings = _.map( settings.data, ( value, id ) => ( { id, value } ) );
      this.types    = _.map( types.data, ( value, id ) => ( { id, value } ) );
    },
    save()
    {
      this.$emit( 'input', this.preferences );
      this.open = false;
    },
    cancel()
    {
      this.preferences = this.original;
      this.open        = false;
    },
    fieldId: field => _.camelCase( `input_${field.model}` ),
  },
  computed: {
    jobRole() { return _.find(this.jobRoles, ['id', this.preferences.job_role]); },
    setting() { return _.find(this.settings, ['id', this.preferences.setting]); },
    type() { return _.find(this.types, ['id', this.preferences.type]); },
  },
};
</script>

<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  .sublabel {
    font-size: 14px;
  }
</style>
