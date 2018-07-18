<template>
  <el-tree
    ref="tree"
    v-bind="$attrs"
    :data="data"
    :props=" { label: labelKey }"
    default-expand-all
    :default-checked-keys="value"
    :expand-on-click-node="false"
    :check-on-click-node="!elForm.disabled"
    show-checkbox
    node-key="id"
    @check="handleCheckChange">
  </el-tree>
</template>

<script>
  export default {
    name: 'el-tree-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection

    // Also, inject the form so that we are able to restrict user from selecting an option
    // while it is disabled
    inject: ['$validator', 'elForm'],

    props: {
      data: {
        type: Array,
        required: true
      },
      labelKey: String,
      value: Array
    },

    watch: {
      // make sure that when the value is changed that we update the checked keys
      // as the value may have changed but the checkboxes won't reflect the new values
      value: function(newVal) {
        this.$refs.tree.setCheckedKeys(newVal);
      }
    },

    methods: {
      updateValue: function (value) {
        // update parent data so that we can still v-model on the parent
        // 2018-07-17 @note: this is a limitation of ElementUI
        // that doesn't accept v-model on el-tree elements
        this.$emit('input', value);
      },

      handleCheckChange(checkedNode, treeCheckedStatus) {
        let checkedKeys = [
          treeCheckedStatus.checkedKeys,
          ...treeCheckedStatus.halfCheckedKeys
        ];
        this.updateValue(...checkedKeys);
      }
    }
  };
</script>

<style lang="scss">
  // @note: a class "is-disabled" has been added manually to the form
  // in order to be able to target the treeitems when its disabled
  .el-form.is-disabled [role="treeitem"] .el-tree-node__content {
    cursor: not-allowed;
  }
</style>
