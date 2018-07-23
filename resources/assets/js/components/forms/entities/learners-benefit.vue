<template>
  <div class="learners-benefit">
    <el-form-item-wrap
      :label="trans('forms.business_case.learners_benefit_type.label')"
      :prop="`${fieldNamePrefix}.learners_benefit_type_id`"
      :classes="['has-other']"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.learners_benefit_type.description')">
        </el-popover-wrap>
      </span>
      <div class="wrap-with-errors">
        <el-select-wrap
          v-model="form.learners_benefit_type_id"
          :isLoading="isLoading"
          :name="`${fieldNamePrefix}.learners_benefit_type_id`"
          :data-vv-as="trans('forms.business_case.learners_benefit_type.label')"
          v-validate="{ rules: { required: !this.isLearnersBenefitTypeOther} }"
          :options="data.learnersBenefitTypeServer"
        />
        <form-error :name="`${fieldNamePrefix}.learners_benefit_type_id`"></form-error>
      </div>
      <el-input-other-wrap
        :data-vv-as="trans('entities.form.other')"
        :name="'learners_benefit_type_other_' + index"
        v-model="form.learners_benefit_type_other"
        v-validate="{ rules: { required: this.isLearnersBenefitTypeOther} }"
        :isChecked.sync="isLearnersBenefitTypeOther"
        maxlength="100">
      </el-input-other-wrap>
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.business_case.learners_benefit_rationale.label')"
      :prop="`${fieldNamePrefix}.rationale`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.learners_benefit_rationale.description')">
        </el-popover-wrap>
      </span>
      <el-input-wrap
        v-model="form.rationale"
        :data-vv-as="trans('forms.business_case.learners_benefit_rationale.label')"
        :name="`${fieldNamePrefix}.rationale`"
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
  import ElSelectWrap from '../el-select-wrap';
  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'learners-benefit',

    components: { FormError, ElFormItemWrap, ElSelectWrap, ElInputWrap, ElInputOtherWrap, ElPopoverWrap },

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
      fieldNamePrefix() {
        return 'learners_benefits.' + this.index;
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
        this.isLearnersBenefitTypeOther = !!this.form.learners_benefit_type_other;
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          learners_benefit_type_id: null,
          learners_benefit_type_other: null,
          rationale: null
        },
        isLearnersBenefitTypeOther: false
      };
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isLearnersBenefitTypeOther = !!this.form.learners_benefit_type_other;
    }
  };
</script>

<style lang="scss">

</style>
