<template>
  <div class="planned-product">
    <el-form-item-wrap
      :label="trans('forms.architecture_plan.type.label')"
      :prop="`${fieldNamePrefix}.type_id`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.architecture_plan.type.description')"
          :help="trans('forms.architecture_plan.type.help')">
        </el-popover-wrap>
      </span>
      <el-cascader
        v-model="typeModel"
        :name="`${fieldNamePrefix}.type_id`"
        v-loading="isLoading"
        element-loading-spinner="el-icon-loading"
        v-validate="'required'"
        :data-vv-as="trans('forms.architecture_plan.type.label')"
        :options="data.typeList"
        :props="typeOptions">
      </el-cascader>
      <form-error :name="`${fieldNamePrefix}.type_id`"></form-error>
    </el-form-item-wrap>
    <el-form-item-wrap
      :label="trans('forms.architecture_plan.quantity.label')"
      :prop="`${fieldNamePrefix}.quantity`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.architecture_plan.quantity.description')">
        </el-popover-wrap>
      </span>
      <el-input-number
        :name="`${fieldNamePrefix}.quantity`"
        v-model="form.quantity"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.architecture_plan.quantity.label')"
        v-mask="'###'"
        :min="1"
        :max="100">
      </el-input-number>
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElInputWrap from '../el-input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'planned-products',

    components: { FormError, ElFormItemWrap, ElInputWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      data: {
        type: Object,
        required: true
      },
      index: {
        type: Number,
        required: true
      },
      value: Object,
      isLoading: {
        type: Boolean,
        required: true
      }
    },

    computed: {
      fieldNamePrefix() {
        return 'planned_products.' + this.index;
      },

      form: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      },

      typeModel: {
        get() {
          // make sure to not return an array of nulls here
          // since vee-validate would think that it is a valide value
          if (!_.isNumber(this.form.type_id) && !_.isNumber(this.form.sub_type_id)) {
            return null;
          }
          return [this.form.type_id, this.form.sub_type_id];
        },
        set(value) {
          let typeId = value[0];
          let subTypeId = value[1];

          [this.form.type_id, this.form.sub_type_id] = [typeId, subTypeId];
        }
      }
    },

    watch: {
      /**
       * When the data changes, remove empty children
       * so that the cascader doesn't try to populate them
       */
      'data.typeList': function(list) {
        let formattedList = _.cloneDeep(list);
        _.forEach(formattedList, item => {
          _.forEach(item.children, itemSub => {
            if (itemSub.children && itemSub.children.length === 0) {
              delete itemSub.children;
            }
          });
        });
        this.data.typeList = formattedList;
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          type_id: null,
          sub_type_id: null,
          quantity: null
        },

        typeOptions: {
          label: 'name',
          value: 'id'
        }
      };
    }
  };
</script>

<style lang="scss">
  .planned-product {
    .el-cascader {
      min-width: 400px;
    }
  }
</style>
