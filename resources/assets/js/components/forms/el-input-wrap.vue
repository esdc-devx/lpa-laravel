<template>
  <div class="el-input-wrap">
    <el-input :class="{ 'is-error': verrors.has(this.$attrs.name) }" ref="input" :autosize="{ minRows: 2 }" v-bind="$attrs" :value="value" @input.native="updateValue($event.target.value)" :maxlength="charsLimit">
      <slot></slot>
    </el-input>
    <div class="input-infos">
      <form-error :name="this.$attrs.name"></form-error>
      <span v-if="maxlength" class="char-count">
        {{ charCount }} / {{ charsLimit }}
      </span>
    </div>
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'el-input-wrap',

    inheritAttrs: false,

    components: { FormError },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      maxlength: String,
      value: String
    },

    computed: {
      charCount() {
        if (this.value) {
          return this.value.length;
        }
        return 0;
      },
      charsLimit() {
        return this.maxlength;
      }
    },

    methods: {
      updateValue(value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('input', value);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .el-input-wrap {
    display: flex;
    flex-direction: column;
    .char-count {
      color: $--color-text-secondary;
    }
    .el-form-item__error {
      position: relative;
      top: 0;
    }
    .input-infos {
      display: flex;
      justify-content: flex-end;
    }
  }
</style>
