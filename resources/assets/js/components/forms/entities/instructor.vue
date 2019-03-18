<template>
  <div class="instructor">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.required_profile`"
      contextPath="forms.operational_details.instructors.required_profile"
      required
    >
      <input-localized
        :modelEn.sync="form.required_profile_en"
        :modelFr.sync="form.required_profile_fr"
        :validate="'required'"
        :dataVvAsEn="trans('forms.operational_details.instructors.required_profile.label')"
        :dataVvAsFr="trans('forms.operational_details.instructors.required_profile.label')"
        :nameEn="`${fieldNamePrefix}.required_profile_en`"
        :nameFr="`${fieldNamePrefix}.required_profile_fr`"
        maxlength="1250"
        type="textarea"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.schedule`"
      contextPath="forms.operational_details.instructors.schedule"
      required
    >
      <input-localized
        :modelEn.sync="form.schedule_en"
        :modelFr.sync="form.schedule_fr"
        :validate="'required'"
        :dataVvAsEn="trans('forms.operational_details.instructors.schedule.label')"
        :dataVvAsFr="trans('forms.operational_details.instructors.schedule.label')"
        :nameEn="`${fieldNamePrefix}.schedule_en`"
        :nameFr="`${fieldNamePrefix}.schedule_fr`"
        maxlength="1250"
        type="textarea"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputLocalized from '../input-localized';

  export default {
    name: 'instructor',

    components: { ElFormItemWrap, ElSelectWrap, InputLocalized },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      index: {
        type: Number,
        required: true
      },
      value: {
        type: Object
      }
    },

    computed: {
      fieldNamePrefix() {
        return 'instructors.' + this.index;
      },
      form: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          required_profile_en: null,
          required_profile_fr: null,
          schedule_en: null,
          schedule_fr: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
