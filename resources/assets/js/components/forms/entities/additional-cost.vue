<template>
  <div class="additional-cost">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.rationale`"
      contextPath="forms.design.additional_costs.rationale"
      required
    >
      <input-wrap
        v-model="form.rationale"
        v-validate="'required'"
        :data-vv-as="trans('forms.design.additional_costs.rationale.label')"
        :name="`${fieldNamePrefix}.rationale`"
        maxlength="1250"
        type="textarea"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.costs`"
      contextPath="forms.design.additional_costs.costs"
      required
    >
      <input-wrap
        v-model="form.costs"
        :name="`${fieldNamePrefix}.costs`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.design.additional_costs.costs.label')"
        :min="1"
        :max="999999"
        type="number"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'additional-cost',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap },

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
        return 'additional_costs.' + this.index;
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
          rationale: null,
          costs: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
