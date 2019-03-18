<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="providers">
      <span slot="label" :class="{'is-error': errorTabs.includes('providers') }">
        {{ trans('forms.solution_development.tabs.providers') }}
      </span>
      <h3>{{ trans('forms.solution_development.tabs.providers') }}</h3>

      <el-form-item-wrap
        prop="design_provider_id"
        :classes="['has-other']"
        contextPath="forms.solution_development.design_provider"
        required
      >
        <el-select-other-wrap
          :modelSelect.sync="form.design_provider_id"
          nameSelect="design_provider"
          :dataVvAsSelect="trans('forms.solution_development.design_provider.label')"
          :validateSelect="{ required: !this.isDesignProviderOther }"
          :options="contentProviderList"
          sorted

          :modelOther.sync="form.design_provider_other"
          nameOther="design_provider_other"
          :dataVvAsOther="trans('forms.solution_development.design_provider_other.label')"
          :validateOther="{ required: this.isDesignProviderOther }"
          :isChecked.sync="isDesignProviderOther"
          maxlength="100"
        />
      </el-form-item-wrap>

      <el-form-item-wrap
        prop="implementation_provider_id"
        :classes="['has-other']"
        contextPath="forms.solution_development.implementation_provider"
        required
      >
        <el-select-other-wrap
          :modelSelect.sync="form.implementation_provider_id"
          nameSelect="implementation_provider"
          :dataVvAsSelect="trans('forms.solution_development.implementation_provider.label')"
          :validateSelect="{ required: !this.isImplementationProviderOther }"
          :options="contentProviderList"
          sorted

          :modelOther.sync="form.implementation_provider_other"
          nameOther="implementation_provider_other"
          :dataVvAsOther="trans('forms.solution_development.implementation_provider_other.label')"
          :validateOther="{ required: this.isImplementationProviderOther }"
          :isChecked.sync="isImplementationProviderOther"
          maxlength="100"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="effort">
      <span slot="label" :class="{'is-error': errorTabs.includes('effort') }">
        {{ trans('forms.solution_development.tabs.effort') }}
      </span>
      <h3>{{ trans('forms.solution_development.tabs.effort') }}</h3>
      <el-form-item-wrap
        prop="effort_required"
        contextPath="forms.solution_development.effort_required"
        required
      >
        <duration
          isRequired
          v-model="form.effort_required"
          :hoursMaxValue="10000"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="quality_assurance">
      <span slot="label" :class="{'is-error': errorTabs.includes('quality_assurance') }">
        {{ trans('forms.solution_development.tabs.quality_assurance') }}
      </span>
      <h3>{{ trans('forms.solution_development.tabs.quality_assurance') }}</h3>

      <el-form-item-wrap
        prop="language_content_qa_completed"
        contextPath="forms.solution_development.language_content_qa_completed"
        required
      >
        <yes-no
          v-model="form.language_content_qa_completed"
          v-validate="'required'"
          :data-vv-as="trans('forms.solution_development.language_content_qa_completed.label')"
          name="language_content_qa_completed"
        />
      </el-form-item-wrap>

      <el-form-item-wrap
        prop="instructional_designer_qa_completed"
        contextPath="forms.solution_development.instructional_designer_qa_completed"
        required
      >
        <yes-no
          v-model="form.instructional_designer_qa_completed"
          v-validate="'required'"
          :data-vv-as="trans('forms.solution_development.instructional_designer_qa_completed.label')"
          name="instructional_designer_qa_completed"
        />
      </el-form-item-wrap>

      <el-form-item-wrap
        prop="functional_qa_completed"
        contextPath="forms.solution_development.functional_qa_completed"
        required
      >
        <yes-no
          v-model="form.functional_qa_completed"
          v-validate="'required'"
          :data-vv-as="trans('forms.solution_development.functional_qa_completed.label')"
          name="functional_qa_completed"
        />
      </el-form-item-wrap>

      <el-form-item-wrap
        prop="accessibility_qa_completed"
        contextPath="forms.solution_development.accessibility_qa_completed"
        required
      >
        <yes-no
          v-model="form.accessibility_qa_completed"
          v-validate="'required'"
          :data-vv-as="trans('forms.solution_development.accessibility_qa_completed.label')"
          name="accessibility_qa_completed"
        />
      </el-form-item-wrap>

      <el-form-item-wrap
        prop="client_acceptance_test_completed"
        contextPath="forms.solution_development.client_acceptance_test_completed"
        required
      >
        <yes-no
          v-model="form.client_acceptance_test_completed"
          v-validate="'required'"
          :data-vv-as="trans('forms.solution_development.client_acceptance_test_completed.label')"
          name="client_acceptance_test_completed"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="comments">
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.solution_development.tabs.comments') }}
      </span>
      <h3>{{ trans('forms.solution_development.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.solution_development.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.solution_development.comments.label')"
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
    await store.dispatch('lists/loadLists', [
      'content-provider'
    ]);
  };

  export default {
    name: 'solution-development',

    extends: Form,

    watch: {
      form: function () {
        this.isDesignProviderOther = !!this.form.design_provider_other;
        this.isImplementationProviderOther = !!this.form.implementation_provider_other;
      }
    },

    data() {
      return {
        isDesignProviderOther: false,
        isImplementationProviderOther: false
      }
    },

    computed: {
      contentProviderList() {
        return this.getList('content-provider');
      }
    },

    methods: {
      loadData,

      bindCheckboxes() {
        this.isDesignProviderOther = !!this.form.design_provider_other;
        this.isImplementationProviderOther = !!this.form.implementation_provider_other;
      }
    }
  };
</script>

<style lang="scss">

</style>
