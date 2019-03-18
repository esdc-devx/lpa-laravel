<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="planned_product">
      <span slot="label" :class="{'is-error': errorTabs.includes('planned_product') }">
        {{ $tc('forms.planned_product_list.tabs.planned_product', 2) }}
      </span>
      <h3>{{ $tc('forms.planned_product_list.tabs.planned_product', 2) }}</h3>
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
      <h3>{{ trans('forms.planned_product_list.tabs.comments') }}</h3>
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
  import Form from '../form.vue';

  const loadData = async () => {
    await store.dispatch('lists/loadList', 'learning-product-type');
  };

  export default {
    name: 'planned-product-list',

    extends: Form,

    computed: {
      typeList() {
        return this.getList('learning-product-type');
      }
    },

    methods: {
      loadData,

      onTypeChange(value) {
        let type = value[0];
        let subType = value[1];

        [this.form.type, this.form.subType] = [type, subType];
      }
    }
  };
</script>

<style lang="scss">

</style>
