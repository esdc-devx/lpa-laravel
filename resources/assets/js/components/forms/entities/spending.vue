<template>
  <div class="spending">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.internal_resource_id`"
      contextPath="forms.business_case.spendings.internal_resource"
      required
    >
      <el-select-wrap
        v-model="form.internal_resource_id"
        :name="`${fieldNamePrefix}.internal_resource_id`"
        :data-vv-as="trans('forms.business_case.spendings.internal_resource.label')"
        v-validate="'required'"
        :options="data.internalResourceList"
        sorted
      />
    </el-form-item-wrap>

    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.cost_description`"
      contextPath="forms.business_case.spendings.cost_description"
      required
    >
      <input-wrap
        v-model="form.cost_description"
        v-validate="'required'"
        :data-vv-as="trans('forms.business_case.spendings.cost_description.label')"
        :name="`${fieldNamePrefix}.cost_description`"
        maxlength="250"
        type="textarea"
        required
      />
    </el-form-item-wrap>

    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.cost`"
      contextPath="forms.business_case.spendings.cost"
      required
    >
      <input-wrap
        v-model="form.cost"
        :name="`${fieldNamePrefix}.cost`"
        v-validate="{ required: true, numeric: true, regex: /[0-9]{1,6}/ }"
        :data-vv-as="trans('forms.business_case.spendings.cost.label')"
        v-mask="'######'"
        :min="0"
        :max="999999"
        type="number"
      />
    </el-form-item-wrap>

    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.recurrence_id`"
      contextPath="forms.business_case.spendings.recurrence"
      required
    >
      <el-select-wrap
        v-model="form.recurrence_id"
        :name="`${fieldNamePrefix}.recurrence_id`"
        :data-vv-as="trans('forms.business_case.spendings.recurrence.label')"
        v-validate="'required'"
        :options="data.recurrenceList"
        sorted
      />
    </el-form-item-wrap>

    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.comments`"
      contextPath="forms.business_case.spendings.comments"
    >
      <input-wrap
        v-model="form.comments"
        :data-vv-as="trans('forms.business_case.spendings.comments.label')"
        :name="`${fieldNamePrefix}.comments`"
        maxlength="1250"
        v-validate="''"
        type="textarea"
        required
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'spending',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap },

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
        type: Object
      }
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
