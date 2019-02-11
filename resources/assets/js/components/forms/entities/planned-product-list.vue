<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="planned_product">
      <span slot="label" :class="{'is-error': errorTabs.includes('planned_product') }">
        {{ $tc('forms.planned_product_list.tabs.planned_product', 2) }}
      </span>
      <form-section-group
        v-model="form.planned_products"
        entityForm="planned-product-list"
        entitySection="planned-product"
        :data="{
          typeList
        }"
        :min="1"
      />
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.planned_product_list.tabs.comments') }}
      </span>
      <h2>{{ trans('forms.planned_product_list.tabs.comments') }}</h2>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.planned_product_list.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.planned_product_list.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapGetters } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import FormSectionGroup from '../form-section-group';
  import InputWrap from '../input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  const loadData = async () => {
    await store.dispatch('lists/loadList', 'learning-product-type');
  };

  export default {
    name: 'planned-product-list',

    components: { FormError, FormSectionGroup, ElFormItemWrap, InputWrap, ElPopoverWrap },

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
      },

      typeList() {
        return this.getList('learning-product-type');
      }
    },

    methods: {
      onTypeChange(value) {
        let type = value[0];
        let subType = value[1];

        [this.form.type, this.form.subType] = [type, subType];
      },

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', loadData);
    },

    beforeCreate() {
      loadData();
    },

    mounted() {
      this.$emit('mounted');
      EventBus.$on('Store:languageUpdate', loadData);
    }
  };
</script>

<style lang="scss">

</style>
