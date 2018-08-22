<template>
  <div class="departmental-benefit">
    <el-form-item-wrap
      :label="trans('forms.business_case.departmental_benefit_type.label')"
      :prop="`${fieldNamePrefix}.departmental_benefit_type_id`"
      :classes="['has-other']"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departmental_benefit_type.description')">
        </el-popover-wrap>
        <span class="instruction">
            {{ trans('forms.business_case.departmental_benefit_type.instruction') }}
        </span>
      </span>
      <el-select-other-wrap
        :modelSelect.sync="form.departmental_benefit_type_id"
        :nameSelect="`${fieldNamePrefix}.departmental_benefit_type_id`"
        :dataVVasSelect="trans('forms.business_case.departmental_benefit_type.label')"
        :validateSelect="{ required: !this.isDepartmentalBenefitTypeOther }"
        :options="data.departmentalBenefitTypeList"
        sorted

        :modelOther.sync="form.departmental_benefit_type_other"
        :nameOther="`${fieldNamePrefix}.departmental_benefit_type_other`"
        :dataVVasOther="trans('forms.business_case.departmental_benefit_type_other.label')"
        :validateOther="{ required: this.isDepartmentalBenefitTypeOther }"
        :isChecked.sync="isDepartmentalBenefitTypeOther"
        maxlength="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.business_case.departmental_benefit_rationale.label')"
      :prop="`${fieldNamePrefix}.rationale`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departmental_benefit_rationale.description')">
        </el-popover-wrap>
      </span>
      <input-wrap
        v-model="form.rationale"
        :data-vv-as="trans('forms.business_case.departmental_benefit_rationale.label')"
        :name="`${fieldNamePrefix}.rationale`"
        v-validate="'required'"
        maxlength="1250"
        type="textarea">
      </input-wrap>
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectOtherWrap from '../el-select-other-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'departmental-benefit',

    components: { FormError, ElFormItemWrap, ElSelectOtherWrap, ElSelectWrap, InputWrap, ElPopoverWrap },

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
      value: Object
    },

    computed: {
      fieldNamePrefix() {
        return 'departmental_benefits.' + this.index;
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

    watch: {
      form: function() {
        // make the checkbox react when the form data changes
        this.isDepartmentalBenefitTypeOther = !!this.form.departmental_benefit_type_other;
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          departmental_benefit_type_id: null,
          departmental_benefit_type_other: null,
          rationale: null
        },
        isDepartmentalBenefitTypeOther: false
      };
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isDepartmentalBenefitTypeOther = !!this.form.departmental_benefit_type_other;
    }
  };
</script>

<style lang="scss">

</style>
