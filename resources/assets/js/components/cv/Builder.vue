<template>
  <div class="container-fluid mt-4 mt-lg-5">
    <template v-if="loaded">
      <div class="card card-custom mb-3">
        <div class="card-body">
          <!--<div class="row align-content-center">-->
            <!--<div class="col-12">-->
              <!--<h4 class="d-inline-block">{{ schema.label }}</h4>-->
              <!--<p v-if="schema.sublabel"-->
                 <!--class="d-inline-block text-muted"-->
                 <!--style="font-size: 14px;">-->
                <!--{{ schema.sublabel }}-->
              <!--</p>-->
            <!--</div>-->
            <!--<div class="col-12">-->
              <!--<div class="cv-item">-->
                  <!--<template v-if="model.editing">-->
                      <!--<form @submit.prevent="save">-->
                          <!--<div v-for="(field, index) in schema.fields" :key="fieldId(field)">-->
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

                          <!--<button :type="loading ? 'button' : 'submit'" class="btn btn-action w-25">-->
                              <!--<loading-icon v-if="loading" />-->
                              <!--<template v-else>Save</template>-->
                          <!--</button>-->
                          <!--<button type="button" class="btn btn-link" @click="cancel">Cancel</button>-->
                      <!--</form>-->
                  <!--</template>-->
                  <!--<template v-else>-->
                      <!--<div class="cv-item-inner">-->
                          <!--&lt;!&ndash; PREFERENCES &ndash;&gt;-->
                          <!--<template v-if="schema.name === 'preferences'">-->
                              <!--<template v-if="model.job_role">-->
                                  <!--<small><b>Job Role</b></small>-->
                                  <!--<p class="my-1">{{ schema.fields[0].data[model.job_role-1].name }}</p>-->
                              <!--</template>-->
                              <!--<template v-if="model.setting">-->
                                  <!--<small><b>Job Setting</b></small>-->
                                  <!--<p class="my-1">{{ schema.fields[1].data[model.setting-1].name }}</p>-->
                              <!--</template>-->
                              <!--<template v-if="model.type">-->
                                  <!--<small><b>Job Type</b></small>-->
                                  <!--<p class="my-1">{{ schema.fields[2].data[model.type-1].name }}</p>-->
                              <!--</template>-->
                              <!--<small><b>Relocation</b></small>-->
                              <!--<p class="my-1">-->
                                  <!--<template v-if="model.willing_to_relocate">Willing to relocate</template>-->
                                  <!--<template v-else>Not willing to relocate</template>-->
                              <!--</p>-->
                          <!--</template>-->
                          <!--&lt;!&ndash; EDUCATION &ndash;&gt;-->
                          <!--<template v-if="schema.name === 'education'">-->
                              <!--<p class="my-1">{{ model.degree }} in {{ model.field_of_study }}</p>-->
                              <!--<p class="my-1">{{ model.school_name }} - {{ model.location }}</p>-->
                              <!--<template v-if="validDate(model.end_date)">-->
                                  <!--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                              <!--<template v-else>-->
                                  <!--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                          <!--</template>-->
                          <!--&lt;!&ndash; WORK EXPERIENCE &ndash;&gt;-->
                          <!--<template v-if="schema.name === 'work_experience'">-->
                              <!--<p class="my-1">{{ model.job_title }} at {{ model.company_name }}</p>-->
                              <!--<p class="my-1">{{ model.location }}</p>-->
                              <!--<template v-if="validDate(model.end_date)">-->
                                  <!--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                              <!--<template v-else>-->
                                  <!--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                              <!--<p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>-->
                          <!--</template>-->
                          <!--&lt;!&ndash; CERTIFICATIONS / LICENSES &ndash;&gt;-->
                          <!--<template v-if="schema.name === 'certifications'">-->
                              <!--<p class="my-1">{{ model.title }}</p>-->
                              <!--<template v-if="validDate(model.end_date)">-->
                                  <!--<p class="my-1">{{ formatDate(model.start_date, 'MMMM YYYY') }} to {{ formatDate(model.end_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                              <!--<template v-else>-->
                                  <!--<p class="my-1">Started {{ formatDate(model.start_date, 'MMMM YYYY') }}</p>-->
                              <!--</template>-->
                              <!--<p v-if="model.description" class="my-1">{{ model.description | truncate(50) }}</p>-->
                              <!--<a :href="model.url" class="btn btn-link">View File</a>-->
                          <!--</template>-->
                      <!--</div>-->
                      <!--<button v-if="multiple" class="btn btn-link btn-sm float-right" @click="$emit('delete-cv-item', index)"><span class="oi oi-delete"></span></button>-->
                      <!--<button class="btn btn-link btn-sm float-right" @click="edit"><span class="oi oi-pencil"></span></button>-->
                  <!--</template>-->
              <!--</div>-->
            <!--</div>-->
          <!--</div>-->
        </div>
      </div>
      <PreferencesEditor :model="cv.preferences" />
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
import PreferencesEditor from './Preferences.vue';

export default {
  components: {
    PreferencesEditor,
  },
  data()
  {
    return {
      cv: {},

      loaded: false,
      saving: true,
    };
  },
  computed: {},
  mounted()
  {
    this.load().then( () =>
    {
      this.loaded = true;

      this.$nextTick( () =>
      {
        // DOM finished loading
      } );
    } );
  },
  methods: {
    async load()
    {
      const requests = [
        axios.get( route( 'cv.get.full' ) ),
      ];

      const results = ( await Promise.all( requests ) ).map( r => r.data );

      this.$set( this, 'cv', results[ 0 ] );
    },
    async save()
    {
      this.saving = true;

      try
      {
        const response = await axios.post( route( 'cv.save' ), { cv: this.cv } );
        if ( response.status === 204 )
          toastr.success( 'Saved' );
        else throw response;
      }
      catch ( error )
      {
        console.error( error );
        this.$swal( {
          type: 'error',
          title: 'Error While Saving',
          text: 'There was an error while saving your changes, please try again later.',
          footer: '<small>Try saving again, and if the issue persists, '
            + 'please contact our support team for help.</small>',
        } );
      }
    },
  },
};
</script>

<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  .cv-item {
    background-color: $white;
    padding: 1rem;
    margin: 1rem;
    -webkit-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    -moz-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);

    &-inner {
      display: inline-block;

      .cv-item-education & {
        p:first-child {
          font-size: 1rem;
        }

        p:not(first-child) {
          font-size: 0.85rem;
        }
      }
    }
  }
</style>
