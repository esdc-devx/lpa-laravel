<template>
  <div class="departmental-benefit">
    <el-form-item-wrap
      :label="trans('forms.business_case.departmental_benefit_type.label')"
      :prop="'departmental_benefit_type_' + index"
      :classes="['has-other']"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departmental_benefit_type.description')">
        </el-popover-wrap>
      </span>
      <div class="wrap-with-errors">
        <el-select
          v-model="form.departmental_benefit_type"
          v-loading="isLoading"
          :disabled="isLoading"
          element-loading-spinner="el-icon-loading"
          :name="'departmental_benefit_type_' + index"
          :data-vv-as="trans('forms.business_case.departmental_benefit_type.label')"
          value-key="name"
          v-validate="{ rules: { required: !this.isDepartmentalBenefitTypeOther } }"
          :class="{ 'is-error': verrors.has('departmental_benefit_type') }">
          <el-option
            v-for="item in data.departmentalBenefitTypeServer"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <form-error name="departmental_benefit_type"></form-error>
      </div>
      <el-input-other-wrap
        :data-vv-as="trans('entities.form.other')"
        :name="'departmental_benefit_type_other_' + index"
        v-model="form.departmental_benefit_type_other"
        v-validate="{ rules: { required: this.isDepartmentalBenefitTypeOther} }"
        :isChecked.sync="isDepartmentalBenefitTypeOther"
        maxlength="100">
      </el-input-other-wrap>
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.business_case.departmental_benefit_rationale.label')"
      :prop="'rationale_' + index"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departmental_benefit_rationale.description')">
        </el-popover-wrap>
      </span>
      <el-input-wrap
        v-model="form.rationale"
        :data-vv-as="trans('forms.business_case.departmental_benefit_rationale.label')"
        :name="'rationale_' + index"
        v-validate="'required'"
        maxlength="1250"
        type="textarea">
      </el-input-wrap>
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'departmental-benefit',

    components: { FormError, ElFormItemWrap, ElInputWrap, ElInputOtherWrap, ElPopoverWrap },

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
      value: Object,
      isLoading: {
        type: Boolean,
        required: true
      }
    },

    computed: {
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
        defaults: {
          departmental_benefit_type_id: null,
          departmental_benefit_type: null,
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
