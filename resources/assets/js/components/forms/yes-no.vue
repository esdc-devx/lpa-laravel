<template>
  <div class="yes-no">
    <el-radio-group
      v-bind="$attrs"
      v-model="innerValue"
      :name="name"
    >
      <el-radio-button :label="true">{{ trans('base.actions.yes') }}</el-radio-button>
      <el-radio-button :label="false">{{ trans('base.actions.no') }}</el-radio-button>
    </el-radio-group>
    <form-error :name="name"/>
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'yes-no',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError },

    props: {
      name: {
        type: String,
        required: true
      },
      value: {
        type: String | Number
      }
    },

    data() {
      return {
        innerValue: this.value
      };
    },

    watch: {
      innerValue(val) {
        // affect the computed value so that our field is in sync
        this.$emit('input', val);
      }
    }
  };
</script>

<style lang="scss">

</style>
