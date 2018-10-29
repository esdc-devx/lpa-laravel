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
        :label="trans('forms.planned_product_list.comments.label')"
        prop="comments">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.planned_product_list.comments.description')"
            :help="trans('forms.planned_product_list.comments.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.planned_product_list.comments.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.planned_product_list.comments.label')"
          maxlength="2500"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import FormSectionGroup from '../form-section-group';
  import InputWrap from '../input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';
  import ListsAPI from '@api/lists';

  export default {
    name: 'planned-product-list',

    components: { FormError, FormSectionGroup, ElFormItemWrap, InputWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: Object,
      errorTabs: Array
    },

    data() {
      return {
        typeList: []
      }
    },

    computed: {
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
      onTypeChange(value) {
        let type = value[0];
        let subType = value[1];

        [this.form.type, this.form.subType] = [type, subType];
      },

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async loadData() {
        this.typeList = await ListsAPI.getList('learning-product-type');
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.loadData);
    },

    created() {
      this.loadData();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.loadData);
    }
  };
</script>

<style lang="scss">

</style>
