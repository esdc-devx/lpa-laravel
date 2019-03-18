<template>
  <div class="region">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.region_id`"
      contextPath="forms.offering_and_enrolment_estimates.regions.region"
      required
    >
      <el-select-wrap
        v-model="form.region_id"
        v-validate="'required'"
        :data-vv-as="trans('forms.offering_and_enrolment_estimates.regions.region.label')"
        name="region_id"
        :options="data.regionList"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_bilingual_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_bilingual_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_bilingual_offering_number"
        name="regional_annual_bilingual_offering_number"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.offering_and_enrolment_estimates.regions.regional_annual_bilingual_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_english_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_english_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_english_offering_number"
        name="regional_annual_english_offering_number"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.offering_and_enrolment_estimates.regions.regional_annual_english_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_french_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_french_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_french_offering_number"
        name="regional_annual_french_offering_number"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.offering_and_enrolment_estimates.regions.regional_annual_french_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_simultaneous_interpretation_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_simultaneous_interpretation_offering_number"
      required
      v-if="isCourseInstructorLed()"
    >
      <input-wrap
        v-model="form.regional_annual_simultaneous_interpretation_offering_number"
        name="regional_annual_simultaneous_interpretation_offering_number"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.offering_and_enrolment_estimates.regions.regional_annual_simultaneous_interpretation_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'region',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      data: {
        type: Object,
        required: true
      },
      index: {
        type: Number,
        required: true
      },
      value: {
        type: Object
      }
    },

    computed: {
      ...mapGetters('entities/learningProducts', [
        'isCourse',
        'isCourseInstructorLed',
        'isCourseDistance',
        'isCourseOnlineSelfPaced'
      ]),

      fieldNamePrefix() {
        return 'regions.' + this.index;
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
          region_id: null,
          regional_annual_bilingual_offering_number: null,
          regional_annual_english_offering_number: null,
          regional_annual_french_offering_number: null,
          regional_annual_simultaneous_interpretation_offering_number: null

        }
      };
    },

    watch: {
      form: function () {
        // make the checkbox react when the form data changes
        this.isregionCategoryOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
      }
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isregionCategoryOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
    }
  };
</script>

<style lang="scss">

</style>
