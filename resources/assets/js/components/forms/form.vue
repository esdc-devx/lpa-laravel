<template>

</template>

<script>
  import { mapGetters } from 'vuex';

  import FormError from './error.vue';

  import ElFormItemWrap from './el-form-item-wrap';
  import ElSelectOtherWrap from './el-select-other-wrap';
  import ElSelectWrap from './el-select-wrap';
  import InputWrap from './input-wrap';
  import FormSectionGroup from './form-section-group';
  import ElTreeWrap from './el-tree-wrap';
  import ElPopoverWrap from '../el-popover-wrap';
  import Duration from './duration';
  import InputLocalized from './input-localized';
  import YesNo from './yes-no';

  export default {
    name: 'form',

    components: {
      FormError,
      ElFormItemWrap,
      ElSelectOtherWrap,
      ElSelectWrap,
      InputWrap,
      FormSectionGroup,
      ElTreeWrap,
      ElPopoverWrap,
      Duration,
      InputLocalized,
      YesNo
    },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      processEntityType: {
        type: String,
        required: true
      },
      formData: {
        type: Object
      },
      errorTabs: {
        type: Array
      }
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

    watch: {
      '$store.state.language': function (to, from) {
        this.onLanguageUpdate();
      },

      form: function () {
        this.bindCheckboxes();
      }
    },

    methods: {
      async loadData() {},

      onLanguageUpdate() {
        if (this.loadData) {
          this.loadData.apply(this);
        }
      },

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      bindCheckboxes() {}
    },

    async created() {
      if (this.loadData) {
        await this.loadData();
      }
      this.bindCheckboxes();
    },

    mounted() {
      this.$emit('mounted');
    }
  };
</script>

<style lang="scss">

</style>
