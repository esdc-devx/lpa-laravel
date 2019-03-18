<template>
  <div class="applicable-policy">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.applicable_policies`"
      contextPath="forms.design.applicable_policies.applicable_policy"
      required
    >
      <input-wrap
        v-model="form.name"
        v-validate="'required'"
        :data-vv-as="trans('forms.design.applicable_policies.applicable_policy.label')"
        :name="`${fieldNamePrefix}.applicable_policies`"
        maxlength="255"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'applicable-policy',

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
        return 'applicable_policies.' + this.index;
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
          name: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
