<template>
  <div class="risk">
    <el-form-item-wrap
      :label="trans('forms.business_case.risks.risk_type.label')"
      :prop="`${fieldNamePrefix}.risk_type_id`"
      :classes="['has-other']"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.risks.risk_type.description')">
        </el-popover-wrap>
        <span class="instruction">
          {{ trans('forms.business_case.risks.risk_type.instruction') }}
        </span>
      </span>
      <el-select-other-wrap
        :modelSelect.sync="form.risk_type_id"
        :nameSelect="`${fieldNamePrefix}.risk_type_id`"
        :dataVVasSelect="trans('forms.business_case.risks.risk_type.label')"
        :validateSelect="{ required: !this.isRiskTypeOther }"
        :options="data.riskTypeList"
        sorted

        :modelOther.sync="form.risk_type_other"
        :nameOther="`${fieldNamePrefix}.risk_type_other`"
        :dataVVasOther="trans('forms.business_case.risks.risk_type_other.label')"
        :validateOther="{ required: this.isRiskTypeOther }"
        :isChecked.sync="isRiskTypeOther"
        maxlength="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.business_case.risks.risk_rationale.label')"
      :prop="`${fieldNamePrefix}.rationale`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.risks.risk_rationale.description')"
          :help="trans('forms.business_case.risks.risk_rationale.help')">
        </el-popover-wrap>
        <span class="instruction">
          {{ trans('forms.business_case.risks.risk_rationale.instruction') }}
        </span>
      </span>
      <input-wrap
        v-model="form.rationale"
        v-validate="'required'"
        :data-vv-as="trans('forms.business_case.risks.risk_rationale.label')"
        :name="`${fieldNamePrefix}.rationale`"
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
    name: 'risk',

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
        return 'risks.' + this.index;
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
        this.isRiskTypeOther = !!this.form.risk_type_other;
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          risk_type_id: null,
          rationale: null
        },
        isRiskTypeOther: false
      };
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isRiskTypeOther = !!this.form.risk_type_other;
    }
  };
</script>

<style lang="scss">

</style>
