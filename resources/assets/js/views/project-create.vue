<template>
  <div class="project-create content">
    <h2>{{ trans('base.navigation.projects_create') }}</h2>
    <el-form :model="form" ref="form" label-width="30%" @submit.native.prevent :disabled="isFormDisabled">
      <el-form-item :label="trans('entities.project.name')" for="name" :class="['is-required', {'is-error': verrors.collect('name').length }]"  prop="name">
        <el-input
          v-model="form.name"
          v-validate="'required'"
          id="name"
          name="name"
          auto-complete="off"
          autofocus>
        </el-input>
        <form-error v-for="error in verrors.collect('name')" :key="error.id">{{ error }}</form-error>
      </el-form-item>
      <el-form-item :label="$tc('base.entities.organizational_units')" for="organizationalUnits" :class="['is-required', {'is-error': verrors.collect('organizationalUnits').length }]" prop="organizational_units">
        <el-select
          :disabled="form.organizational_units.length <= 1"
          v-validate="'required'"
          v-model="form.organizational_unit"
          id="organizationalUnits"
          name="organizationalUnits">
          <el-option
            v-for="item in form.organizational_units"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <form-error v-for="error in verrors.collect('organizationalUnits')" :key="error.id">{{ error }}</form-error>
      </el-form-item>

      <el-form-item class="form-footer">
        <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSaving" type="primary" @click="onSubmit()">{{ trans('base.actions.create') }}</el-button>
        <el-button :disabled="isFormDisabled" @click="goBack()">{{ trans('base.actions.cancel') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import EventBus from '../event-bus.js';
  import FormError from '../components/forms/error.vue';
  import FormUtils from '../mixins/form/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project-create',

    mixins: [ FormUtils ],

    components: { FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        organizationalUnits: `${namespace}/organizationalUnits`
      })
    },

    data() {
      return {
        form: {
          name: '',
          organizational_units: [{
            value: 'FC1',
            label: 'Functional Community 1'
          },
          {
            value: 'FC2',
            label: 'Functional Community 2'
          },
          {
            value: 'transformation',
            label: 'Transformation'
          }]
        }
      }
    },

    methods: {
      // Form handlers
      onSubmit() {
        this.submit(this.create);
      },

      async create() {
        // @todo: add call to projects/create
      },

      manageBackendErrors(errors) {
        let fieldBag;
        for (let fieldName in errors) {
          fieldBag = errors[fieldName];
          for (let j = 0; j < fieldBag.length; j++) {
            this.verrors.add({field: fieldName, msg: fieldBag[j]})
          }
        }
        this.focusOnError();
      },

      // Navigation
      goBack() {
        this.$helpers.throttleAction(() => {
          this.$router.push(`/${this.language}/projects`);
        });
      },
    },

    async mounted() {
      EventBus.$emit('App:ready');
      // @todo: listen to language change and translate the organizational-units
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';
  .project-create {
    margin: 0 auto;
    h2 {
      text-align: center;
    }

    .el-form {
      width: 800px;
      margin: 0 auto;
      .el-select {
        width: 75%;
      }
    }
  }
  .el-autocomplete-suggestion.name-autocomplete {
    li {
      line-height: 20px;
      padding: 7px 10px;

      .autocomplete-popper-inner-wrap {
        overflow: hidden;
        .value {
          text-overflow: ellipsis;
          overflow: hidden;
        }
        .link {
          font-size: 12px;
          color: #b4b4b4;
        }
      }
    }
  }
</style>
