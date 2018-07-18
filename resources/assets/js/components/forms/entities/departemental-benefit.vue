<template>
  <div class="departmental-benefit">
    <el-form-item-wrap
      :label="trans('forms.business_case.departemental_benefit_type.label')"
      prop="departemental_benefit_type"
      :classes="['has-other']"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departemental_benefit_type.description')">
        </el-popover-wrap>
      </span>
      <div class="wrap-with-errors">
        <el-select
          v-model="form.departemental_benefit_type"
          v-loading="isInfoLoading"
          :disabled="isInfoLoading"
          element-loading-spinner="el-icon-loading"
          name="departemental_benefit_type"
          :data-vv-as="trans('forms.business_case.departemental_benefit_type.label')"
          value-key="name"
          v-validate="{ rules: { required: !this.isRequestSourceOther} }"
          :class="{ 'is-error': verrors.has('departemental_benefit_type') }"
          multiple>
          <el-option
            v-for="item in requestSourceServer"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <form-error name="departemental_benefit_type"></form-error>
      </div>
      <el-input-other-wrap
        :data-vv-as="trans('entities.form.other')"
        name="request_source_other"
        v-model="form.request_source_other"
        v-validate="{ rules: { required: this.isRequestSourceOther} }"
        :isChecked.sync="isRequestSourceOther"
        maxlength="100">
      </el-input-other-wrap>
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.business_case.departemental_benefit_rationale.label')"
      prop="departemental_benefit_rationale"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.departemental_benefit_rationale.description')">
        </el-popover-wrap>
      </span>
      <el-input-wrap
        v-model="form.departemental_benefit_rationale"
        :data-vv-as="trans('forms.business_case.departemental_benefit_rationale.label')"
        name="departemental_benefit_rationale"
        v-validate="'required'"
        maxlength="1250"
        type="textarea">
      </el-input-wrap>
    </el-form-item-wrap>
  </div>
</template>

<script>
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

    data() {
      return {
        isInfoLoading: true,
        requestSourceServer: [],
        isRequestSourceOther: false,
        form: {}
      };
    }
  };
</script>

<style lang="scss">

</style>
