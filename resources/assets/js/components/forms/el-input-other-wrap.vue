<template>
  <div class="el-input-other-wrap">
    <el-checkbox v-model="checked" :value="isChecked" @change="onCheckboxChange">
      {{ trans('entities.form.other') }}
    </el-checkbox>
    <el-input-wrap ref="input" :disabled="!isChecked" v-bind="$attrs" :value="value" @input.native="updateValue($event.target.value)">
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
      isChecked: Boolean,
      value: String
    },

    computed: {
      checked: {
        get() {
          return this.isChecked;
        },
        set(val) {
          this.$emit('update:isChecked', val);
        }
      }
    },

    methods: {
      updateValue: function (value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('input', value);
      },

      onCheckboxChange(checked) {
        // update parent property
        // this.$emit('update:isChecked', checked);
        if (checked) {
          this.$nextTick(() => {
            let inputEl = this.$refs.input.$el.querySelector('input');
            let textareaEl = this.$refs.input.$el.querySelector('textarea');
            let el = inputEl || textareaEl;
            el.focus();
          });
        } else {
          // empty the input so that we send null
          // when the checkbox is unchecked
          this.$emit('input', null);
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
      display: inline-table;
      // make sure the line-height is constant here
      // as it needs to be vertically centered with its folowing input
      line-height: 40px !important;
    }
    .el-input-wrap {
      flex: 1;
    }
  }
</style>
