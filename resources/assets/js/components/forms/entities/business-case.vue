<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane label="Business Drivers">
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
      Route <br>
    </el-tab-pane>
    <el-tab-pane label="Proposal">
      Config
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  export default {
    name: 'business-case',

    methods: {
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      }
    }
  };
</script>

<style lang="scss">

</style>
