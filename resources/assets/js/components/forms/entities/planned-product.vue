<template>
  <div class="planned-product">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.type_id`"
      contextPath="forms.planned_product_list.type"
      required
    >
      <el-cascader
        v-model="typeModel"
        :name="`${fieldNamePrefix}.type_id`"
        element-loading-spinner="el-icon-loading"
        v-validate="'required'"
        :data-vv-as="trans('forms.planned_product_list.type.label')"
        :options="data.typeList"
        :props="typeOptions"
      />
      <form-error :name="`${fieldNamePrefix}.type_id`"></form-error>
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.quantity`"
      contextPath="forms.planned_product_list.quantity"
      required
    >
      <input-wrap
        v-model="form.quantity"
        :name="`${fieldNamePrefix}.quantity`"
        v-validate="'required'"
        :data-vv-as="trans('forms.planned_product_list.quantity.label')"
        v-mask="'###'"
        :min="1"
        :max="100"
        type="number"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'planned-products',

    components: { FormError, ElFormItemWrap, ElPopoverWrap, InputWrap },

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
      value: {
        type: Object,
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
      'data.typeList': function (list) {
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
