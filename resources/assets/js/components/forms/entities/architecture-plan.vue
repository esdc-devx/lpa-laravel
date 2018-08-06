<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="planned_product">
      <span slot="label" :class="{'is-error': errorTabs.includes('planned_product') }">
        {{ $tc('forms.architecture_plan.tabs.planned_product', 2) }}
      </span>
      <form-section-group
        v-model="form.planned_products"
        entityForm="architecture_plan"
        entitySection="planned-product"
        :data="{
          typeList
        }"
        :min="1"
        :isLoading="isInfoLoading"
      />
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.architecture_plan.tabs.comments') }}
      </span>
      <h2>{{ trans('forms.architecture_plan.tabs.comments') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.architecture_plan.comment.label')"
        prop="comment">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.architecture_plan.comment.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.architecture_plan.comment.instruction') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.comment"
          name="comment"
          v-validate="''"
          :data-vv-as="trans('forms.architecture_plan.comment.label')"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
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
  import ElInputWrap from '../el-input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'architecture-plan',

    components: { FormError, FormSectionGroup, ElFormItemWrap, ElInputWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: Object,
      errorTabs: Array
    },

    data() {
      return {
        isInfoLoading: true,
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
      ...mapActions([
        'showMainLoading',
        'hideMainLoading'
      ]),

      onTypeChange(value) {
        let type = value[0];
        let subType = value[1];

        [this.form.type, this.form.subType] = [type, subType];
      },

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetchLists() {
        await this.showMainLoading();
        this.isInfoLoading = true;
        let response = await axios.get('lists?include[]=learning-product-type');
        this.typeList = response.data.data['learning-product-type'];
        this.isInfoLoading = false;
        await this.hideMainLoading();
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetchLists);
    },

    async created() {
      await this.showMainLoading();
      this.fetchLists();
      // load all the form fields with data passed in
      // create a new copy without reference so that we don't alter the original values
      this.form = _.cloneDeep(this.formData);
      await this.hideMainLoading();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetchLists);
    }
  };
</script>

<style lang="scss">

</style>
