<template>
  <div class="el-input-other-wrap">
    <el-checkbox v-model="checked" @change="onCheckboxChange">
      {{ trans('entities.form.other') }}
    </el-checkbox>
    <el-input-wrap ref="input" :disabled="!checked" v-bind="$attrs" @input.native="updateValue($event.target.value)">
      <slot></slot>
    </el-input-wrap>
  </div>
</template>

<script>
  import ElInputWrap from './el-input-wrap';

  export default {
    name: 'el-input-other-wrap',

    inheritAttrs: false,

    components: { ElInputWrap },

    props: {
      value: String
    },

    data() {
      return {
        checked: false
      }
    },

    methods: {
      updateValue: function (value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('input', value);
      },

      onCheckboxChange(checked) {
        // update parent property
        this.$emit('update:isChecked', checked);
        if (checked) {
          this.$nextTick(() => {
            let inputEl = this.$refs.input.$el.querySelector('input');
            let textareaEl = this.$refs.input.$el.querySelector('textarea');
            let el = inputEl || textareaEl;
            el.focus();
          });
        }
      }
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';

  .el-input-other-wrap {
    display: flex;
    .el-checkbox {
      margin-left: 20px;
      margin-right: 20px;
    }
    .el-input-wrap {
      flex: 1;
    }
  }
</style>
