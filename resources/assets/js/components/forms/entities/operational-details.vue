<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane></el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapGetters } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectOtherWrap from '../el-select-other-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';
  import FormSectionGroup from '../form-section-group';
  import ElTreeWrap from '../el-tree-wrap';

  const loadData = async () => {

  };

  export default {
    name: 'operational-details',

    components: { FormError, ElFormItemWrap, ElSelectOtherWrap, ElSelectWrap, InputWrap, FormSectionGroup, ElTreeWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: {
        type: Object
      },
      errorTabs: {
        type: Array
      }
    },

    data() {
      return {};
    },

    computed: {
      ...mapGetters('lists', {
        getList: 'list'
      }),

      form: {
        get() {
          return this.formData;
        },
        set(data) {
          this.$emit('update:formData', data);
        }
      }
    },

    methods: {
      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      bindCheckboxes() {

      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', loadData);
      EventBus.$off('FormEntity:discardChanges', this.bindCheckboxes);
    },

    beforeCreate() {
      loadData();
    },

    created() {
      this.bindCheckboxes();
    },

    mounted() {
      this.$emit('mounted');
      EventBus.$on('Store:languageUpdate', loadData);
      EventBus.$on('FormEntity:discardChanges', this.bindCheckboxes);
    }
  };
</script>

<style lang="scss">

</style>
