<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="project_objective">
      <span slot="label" :class="{'is-error': errorTabs.includes('project_objective') }">
        {{ trans('forms.business_case.tabs.project_objective') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.project_objective') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.request_sources.label')"
        prop="request_sources"
        :classes="['has-other']"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.request_sources.description')"
            :help="trans('forms.business_case.request_sources.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.request_sources.instruction') }}
          </span>
        </span>
        <el-select-other-wrap
          :modelSelect.sync="form.request_sources"
          nameSelect="request_sources"
          :dataVVasSelect="trans('forms.business_case.request_sources.label')"
          :validateSelect="{ required: !this.isRequestSourceOther }"
          :options="requestSourceList"
          multiple
          sorted

          :modelOther.sync="form.request_source_other"
          nameOther="request_source_other"
          :dataVVasOther="trans('forms.business_case.request_source_other.label')"
          :validateOther="{ required: this.isRequestSourceOther }"
          :isChecked.sync="isRequestSourceOther"
          maxlength="100"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.business_issue.label')"
        prop="business_issue"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.business_issue.description')"
            :help="trans('forms.business_case.business_issue.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.business_issue.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.business_issue"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.business_issue.label')"
          name="business_issue"
          maxlength="1250"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="proposed_solution">
      <span slot="label" :class="{'is-error': errorTabs.includes('proposed_solution') }">
        {{ trans('forms.business_case.tabs.proposed_solution') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.proposed_solution') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.learning_response_strategy.label')"
        prop="learning_response_strategy"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.learning_response_strategy.description')"
            :help="trans('forms.business_case.learning_response_strategy.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.learning_response_strategy.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.learning_response_strategy"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.learning_response_strategy.label')"
          name="learning_response_strategy"
          maxlength="2500"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.short_term_learning_response.label')"
        prop="short_term_learning_response"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.short_term_learning_response.description')"
            :help="trans('forms.business_case.short_term_learning_response.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.short_term_learning_response.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.short_term_learning_response"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.short_term_learning_response.label')"
          name="short_term_learning_response"
          maxlength="1250"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.medium_term_learning_response.label')"
        prop="medium_term_learning_response"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.medium_term_learning_response.description')"
            :help="trans('forms.business_case.medium_term_learning_response.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.medium_term_learning_response.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.medium_term_learning_response"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.medium_term_learning_response.label')"
          name="medium_term_learning_response"
          maxlength="1250"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.long_term_learning_response.label')"
        prop="long_term_learning_response"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.long_term_learning_response.description')"
            :help="trans('forms.business_case.long_term_learning_response.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.long_term_learning_response.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.long_term_learning_response"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.long_term_learning_response.label')"
          name="long_term_learning_response"
          maxlength="1250"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.school_priorities.label')"
        prop="school_priorities"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.school_priorities.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.school_priorities.instruction') }}
          </span>
        </span>
        <el-select-wrap
          v-model="form.school_priorities"
          name="school_priorities"
          :data-vv-as="trans('forms.business_case.school_priorities.label')"
          v-validate="'required'"
          :options="schoolPriorityList"
          multiple
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.is_required_training.label')"
        prop="is_required_training"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.is_required_training.description')"
            :help="trans('forms.business_case.is_required_training.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.is_required_training.instruction') }}
          </span>
        </span>
        <el-radio-group
          v-model="form.is_required_training"
          :data-vv-as="trans('forms.business_case.is_required_training.label')"
          name="is_required_training"
          v-validate="'required'">
            <el-radio-button :label="0">{{ trans('base.actions.yes') }}</el-radio-button>
            <el-radio-button :label="1">{{ trans('base.actions.no') }}</el-radio-button>
        </el-radio-group>
        <form-error name="is_required_training"></form-error>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="target_audience">
      <span slot="label" :class="{'is-error': errorTabs.includes('target_audience') }">
        {{ trans('forms.business_case.tabs.target_audience') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.target_audience') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.expected_annual_participant_number.label')"
        prop="expected_annual_participant_number"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.expected_annual_participant_number.description')">
          </el-popover-wrap>
        </span>
        <input-wrap
          v-model="form.expected_annual_participant_number"
          name="expected_annual_participant_number"
          v-validate="{ required: true, numeric: true, regex: /[0-9]{1,6}/ }"
          :data-vv-as="trans('forms.business_case.expected_annual_participant_number.label')"
          v-mask="'######'"
          :min="1"
          :max="500000"
          type="number">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.communities.label')"
        prop="communities"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.communities.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.communities.instruction') }}
          </span>
        </span>
        <el-tree-wrap
          name="communities"
          v-model="form.communities"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.communities.label')"
          :data="communityList"
          labelKey="name"
          sorted>
        </el-tree-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="departmental_results_framework">
      <span slot="label" :class="{'is-error': errorTabs.includes('departmental_results_framework') }">
        {{ trans('forms.business_case.tabs.departmental_results_framework') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.departmental_results_framework') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.departmental_results_framework_indicators.label')"
        prop="departmental_results_framework_indicators"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.departmental_results_framework_indicators.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.departmental_results_framework_indicators.instruction') }}
          </span>
        </span>
        <el-tree-wrap
          name="departmental_results_framework_indicators"
          v-model="form.departmental_results_framework_indicators"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.departmental_results_framework_indicators.label')"
          :data="departmentalResultsFrameworkIndicatorList"
          labelKey="name">
        </el-tree-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="costs">
      <span slot="label" :class="{'is-error': errorTabs.includes('costs') }">
        {{ trans('forms.business_case.tabs.costs') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.costs') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.cost_centre.label')"
        prop="cost_centre"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.cost_centre.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.cost_centre.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.cost_centre"
          v-validate="{ required: true, regex: /[A-Z][0-9]{5,5}/ }"
          :data-vv-as="trans('forms.business_case.cost_centre.label')"
          name="cost_centre"
          :placeholder="trans('forms.business_case.cost_centre.hint')"
          v-mask="'A#####'">
        </input-wrap>
      </el-form-item-wrap>
      <form-section-group
        v-model="form.spendings"
        entityForm="business-case"
        entitySection="spending"
        :data="{
          internalResourceList,
          recurrenceList
        }" />
      <el-form-item-wrap
        :label="trans('forms.business_case.other_operational_considerations.label')"
        prop="other_operational_considerations">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.other_operational_considerations.description')">
          </el-popover-wrap>
        </span>
        <input-wrap
          v-model="form.other_operational_considerations"
          v-validate="''"
          :data-vv-as="trans('forms.business_case.other_operational_considerations.label')"
          name="other_operational_considerations"
          maxlength="1250"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="risks">
      <span slot="label" :class="{'is-error': errorTabs.includes('risks') }">
        {{ $tc('forms.business_case.tabs.risk', 2) }}
      </span>
      <form-section-group
        v-model="form.risks"
        entityForm="business-case"
        entitySection="risk"
        :data="{
          riskTypeList
        }"
      />
    </el-tab-pane>

    <el-tab-pane data-name="comments">
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.business_case.tabs.comments') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.comments') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.comments.label')"
        prop="comments">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.comments.description')">
          </el-popover-wrap>
        </span>
        <input-wrap
          v-model="form.comments"
          v-validate="''"
          :data-vv-as="trans('forms.business_case.comments.label')"
          name="comments"
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
  import ElSelectOtherWrap from '../el-select-other-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';
  import FormSectionGroup from '../form-section-group';
  import ElTreeWrap from '../el-tree-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  import ListsAPI from '@api/lists';

  export default {
    name: 'business-case',

    components: { FormError, ElFormItemWrap, ElSelectOtherWrap, ElSelectWrap, InputWrap, FormSectionGroup, ElTreeWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: Object,
      errorTabs: Array
    },

    data() {
      return {
        requestSourceList: [],
        isRequestSourceOther: false,
        schoolPriorityList: [],
        communityList: [],
        departmentalResultsFrameworkIndicatorList: [],
        internalResourceList: [],
        recurrenceList: [],
        isInternalResourceOther: false,
        riskTypeList: []
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
      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetch() {
        await ListsAPI.getLists([
          'request-source',
          'school-priority',
          'community',
          'departmental-results-framework-indicator',
          'internal-resource',
          'recurrence',
          'risk-type'
        ])
        .then(lists => {
          _.forEach(lists, (list, key) => {
            this[`${_.camelCase(key)}List`] = list;
          });
        });
      },

      bindCheckboxes() {
        this.isRequestSourceOther = !!this.form.request_source_other;
        this.isInternalResourceOther = !!this.form.internal_resource_other;
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetch);
      EventBus.$off('FormEntity:discardChanges', this.bindCheckboxes);
    },

    async created() {
      this.fetch();
      this.bindCheckboxes();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetch);
      EventBus.$on('FormEntity:discardChanges', this.bindCheckboxes);
    }
  };
</script>

<style lang="scss">

</style>
