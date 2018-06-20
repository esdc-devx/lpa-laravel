<template>
  <div class="el-input-wrap">
    <el-input ref="input" :autosize="{ minRows: 2 }" v-bind="$attrs" @input.native="updateValue($event.target.value)" :maxlength="charsLimit">
      <slot></slot>
    </el-input>
    <span v-if="maxlength" class="char-count">
      {{ charCount }} / {{ charsLimit }}
    </span>
  </div>
</template>

<script>
  export default {
    name: 'el-input-wrap',

    inheritAttrs: false,

    props: {
      maxlength: {
        type: String,
        default: null
      },
      value: String
    },

    data() {
      return {
        charCount: 0
      }
    },

    computed: {
      charsLimit() {
        return this.maxlength;
      }
    },

    methods: {
      updateValue: function (value) {
        // update the char count
        this.charCount = value.length;
        // update parent data so that we can still v-model on the parent
        this.$emit('input', value);
      }
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  .el-input-wrap {
    display: flex;
    flex-direction: column;
    .char-count {
      align-self: flex-end;
      color: $--color-text-secondary;
    }
  }
</style>
