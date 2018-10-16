<template>
  <div class="spending">
    <el-form-item-wrap
      :label="trans('forms.business_case.spendings.internal_resource.label')"
      :prop="`${fieldNamePrefix}.internal_resource_id`"
      required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.spendings.internal_resource.description')"
            :help="trans('forms.business_case.spendings.internal_resource.help')" />
        </span>
        <el-select-wrap
          v-model="form.internal_resource_id"
          :name="`${fieldNamePrefix}.internal_resource_id`"
          :data-vv-as="trans('forms.business_case.spendings.internal_resource.label')"
          v-validate="'required'"
          :options="data.internalResourceList"
          sorted />
    </el-form-item-wrap>

    <el-form-item-wrap
      :label="trans('forms.business_case.spendings.cost_description.label')"
      :prop="`${fieldNamePrefix}.cost_description`"
      required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.spendings.cost_description.description')"
            :help="trans('forms.business_case.spendings.cost_description.help')" />
        </span>
        <input-wrap
          v-model="form.cost_description"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.spendings.cost_description.label')"
          :name="`${fieldNamePrefix}.cost_description`"
          maxlength="250"
          type="textarea"
          required />
    </el-form-item-wrap>

    <el-form-item-wrap
      :label="trans('forms.business_case.spendings.cost.label')"
      :prop="`${fieldNamePrefix}.cost`"
      required>
      <span slot="label-addons">
        <el-popover-wrap
          :description="trans('forms.business_case.spendings.cost.description')"
          :help="trans('forms.business_case.spendings.cost.help')" />
      </span>
      <input-wrap
        v-model="form.cost"
        :name="`${fieldNamePrefix}.cost`"
        v-validate="{ required: true, numeric: true, regex: /[0-9]{1,6}/ }"
        :data-vv-as="trans('forms.business_case.spendings.cost.label')"
        v-mask="'######'"
        :min="0"
        :max="999999"
        type="number" />
    </el-form-item-wrap>

    <el-form-item-wrap
      :label="trans('forms.business_case.spendings.recurrence.label')"
      :prop="`${fieldNamePrefix}.recurrence_id`"
      required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.spendings.recurrence.description')"
            :help="trans('forms.business_case.spendings.recurrence.help')" />
        </span>
        <el-select-wrap
          v-model="form.recurrence_id"
          :name="`${fieldNamePrefix}.recurrence_id`"
          :data-vv-as="trans('forms.business_case.spendings.recurrence.label')"
          v-validate="'required'"
          :options="data.recurrenceList"
          sorted />
      </el-form-item-wrap>

    <el-form-item-wrap
      :label="trans('forms.business_case.spendings.comments.label')"
      :prop="`${fieldNamePrefix}.comments`">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.spendings.comments.description')"
            :help="trans('forms.business_case.spendings.comments.help')" />
        </span>
        <input-wrap
          v-model="form.comments"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.spendings.comments.label')"
          :name="`${fieldNamePrefix}.comments`"
          maxlength="1250"
          type="textarea"
          required />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'spending',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, ElPopoverWrap },

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
      value: Object
    },

    computed: {
      fieldNamePrefix() {
        return 'spendings.' + this.index;
      },
      form: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          internal_resource_id: null,
          cost_description: null,
          cost: null,
          recurrence_id: null,
          comments: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
