<template>
  <el-tree
    v-bind="$attrs"
    :data="data"
    :props=" { label: labelKey }"
    default-expand-all
    :default-checked-keys="value"
    :expand-on-click-node="false"
    check-on-click-node
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
    inject: ['$validator'],

    props: {
      data: {
        type: Array,
        required: true
      },
      labelKey: String,
      value: Array
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
        this.updateValue(...checkedKeys)
      }
    }
  };
</script>

<style lang="scss">
  // 2018-07-17 @note: this fixes a bug that is currently under investigation on ElementUI's github page:
  //        https://github.com/ElemeFE/element/issues/11827
  [aria-disabled] {
    pointer-events: none;
  }
</style>
