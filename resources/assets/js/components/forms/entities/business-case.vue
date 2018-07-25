<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="business_drivers">
      <span slot="label" :class="{'is-error': errorTabs.includes('business_drivers') }">
        {{ trans('forms.business_case.tabs.business_drivers') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.business_drivers') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.request_sources.label')"
        prop="request_sources"
        :classes="['has-other']"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.request_sources.description')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.request_sources"
            :isLoading="isInfoLoading"
            name="request_sources"
            :data-vv-as="trans('forms.business_case.request_sources.label')"
            v-validate="{ rules: { required: !this.isRequestSourceOther} }"
            :options="requestSourceList"
            multiple
          />
          <form-error name="request_sources"></form-error>
        </div>
        <el-input-other-wrap
          :data-vv-as="trans('entities.form.other')"
          name="request_source_other"
          v-model="form.request_source_other"
          v-validate="{ rules: { required: this.isRequestSourceOther} }"
          :isChecked.sync="isRequestSourceOther"
          maxlength="100">
        </el-input-other-wrap>
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
        <el-input-wrap
          v-model="form.business_issue"
          :data-vv-as="trans('forms.business_case.business_issue.label')"
          name="business_issue"
          v-validate="'required'"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="proposal">
      <span slot="label" :class="{'is-error': errorTabs.includes('proposal') }">
        {{ trans('forms.business_case.tabs.proposal') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.proposal') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.learning_response_strategy.label')"
        prop="learning_response_strategy"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.learning_response_strategy.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.learning_response_strategy.instruction') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.learning_response_strategy"
          :data-vv-as="trans('forms.business_case.learning_response_strategy.label')"
          name="learning_response_strategy"
          v-validate="'required'"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.potential_solution_types.label')"
        prop="potential_solution_types"
        :classes="['has-other']"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.potential_solution_types.description')"
            :help="trans('forms.business_case.potential_solution_types.help')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.potential_solution_types.instruction') }}
          </span>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.potential_solution_types"
            :isLoading="isInfoLoading"
            name="potential_solution_types"
            :data-vv-as="trans('forms.business_case.potential_solution_types.label')"
            v-validate="{ rules: { required: !this.isPotentialSolutionTypesOther} }"
            :options="potentialSolutionTypesList"
            multiple
          />
          <form-error name="potential_solution_types"></form-error>
        </div>
        <el-input-other-wrap
          :data-vv-as="trans('entities.form.other')"
          name="potential_solution_type_other"
          v-model="form.potential_solution_type_other"
          :isChecked.sync="isPotentialSolutionTypesOther"
          v-validate="{ rules: { required: this.isPotentialSolutionTypesOther} }"
          maxlength="1250"
          type="textarea">
        </el-input-other-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.government_priorities.label')"
        prop="government_priorities"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.government_priorities.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.government_priorities.instruction') }}
          </span>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.government_priorities"
            :isLoading="isInfoLoading"
            name="government_priorities"
            :data-vv-as="trans('forms.business_case.government_priorities.label')"
            v-validate="'required'"
            :options="governmentPrioritiesList"
            multiple
          />
          <form-error name="government_priorities"></form-error>
        </div>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.is_required_training.label')"
        prop="is_required_training"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.is_required_training.description')">
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

    <el-tab-pane data-name="timeframe">
      <span slot="label" :class="{'is-error': errorTabs.includes('timeframe') }">
        {{ trans('forms.business_case.tabs.timeframe') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.timeframe') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.timeframe.label')"
        prop="timeframe_id"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.timeframe.description')"
            :help="trans('forms.business_case.timeframe.help')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.timeframe_id"
            :isLoading="isInfoLoading"
            name="timeframe_id"
            :data-vv-as="trans('forms.business_case.timeframe.label')"
            v-validate="'required'"
            :options="timeframeList"
          />
          <form-error name="timeframe_id"></form-error>
        </div>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.timeframe_rationale.label')"
        prop="timeframe_rationale"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.timeframe_rationale.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.timeframe_rationale.instruction') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.timeframe_rationale"
          :data-vv-as="trans('forms.business_case.timeframe_rationale.label')"
          name="timeframe_rationale"
          v-validate="'required'"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="audience">
      <span slot="label" :class="{'is-error': errorTabs.includes('audience') }">
        {{ trans('forms.business_case.tabs.audience') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.audience') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.communities.label')"
        prop="communities"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.communities.description')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-tree-wrap
            name="communities"
            v-model="form.communities"
            :data-vv-as="trans('forms.business_case.communities.label')"
            :data="communitiesList"
            labelKey="name"
            v-validate="'required'">
          </el-tree-wrap>
          <form-error name="communities"></form-error>
        </div>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.expected_annual_participant_number.label')"
        prop="expected_annual_participant_number"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.expected_annual_participant_number.description')">
          </el-popover-wrap>
        </span>
        <el-input-number
          name="expected_annual_participant_number"
          :data-vv-as="trans('forms.business_case.expected_annual_participant_number.label')"
          v-model="form.expected_annual_participant_number"
          v-mask="'######'"
          v-validate="{ required: true, numeric: true, regex: /[0-9]{1,6}/ }"
          :min="1"
          :max="500000">
        </el-input-number>
        <form-error name="expected_annual_participant_number"></form-error>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="departmental_benefit">
      <span slot="label" :class="{'is-error': errorTabs.includes('departmental_benefit') }">
        {{ trans('forms.business_case.tabs.departmental_benefit') }}
      </span>
      <form-section-group
        v-model="form.departmental_benefits"
        entity="departmental-benefit"
        :data="{
          departmentalBenefitTypeList
        }"
        :min="1"
        :isLoading="isInfoLoading"
      />
    </el-tab-pane>

    <el-tab-pane data-name="learners_benefit">
      <span slot="label" :class="{'is-error': errorTabs.includes('learners_benefit') }">
        {{ trans('forms.business_case.tabs.learners_benefit') }}
      </span>
      <form-section-group
        v-model="form.learners_benefits"
        entity="learners-benefit"
        :data="{
          learnersBenefitTypeList
        }"
        :min="1"
        :isLoading="isInfoLoading"
      />
    </el-tab-pane>

    <el-tab-pane data-name="costs">
      <span slot="label" :class="{'is-error': errorTabs.includes('costs') }">
        {{ trans('forms.business_case.tabs.costs') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.costs') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.cost_center.label')"
        prop="cost_center"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.cost_center.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.cost_center.instruction') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.cost_center"
          :data-vv-as="trans('forms.business_case.cost_center.label')"
          name="cost_center"
          :placeholder="trans('forms.business_case.cost_center.hint')"
          v-mask="'A#####'"
          v-validate="{ required: true, regex: /[A-Z][0-9]{5,5}/ }">
        </el-input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.general_ledger_account.label')"
        prop="general_ledger_account"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.general_ledger_account.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.general_ledger_account.instruction') }}
          </span>
        </span>
        <el-input-number
          v-model="form.general_ledger_account"
          :data-vv-as="trans('forms.business_case.general_ledger_account.label')"
          name="general_ledger_account"
          :placeholder="trans('forms.business_case.general_ledger_account.hint')"
          v-mask="'#####'"
          v-validate="{ required: true, numeric: true, regex: /[0-9]{1,5}/ }"
          :min="0"
          :max="99999">
        </el-input-number>
        <form-error name="general_ledger_account"></form-error>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.maintenance_fund.label')"
        prop="maintenance_fund"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.maintenance_fund.description')"
            :help="trans('forms.business_case.maintenance_fund.help')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.maintenance_fund_id"
            :isLoading="isInfoLoading"
            name="maintenance_fund_id"
            :data-vv-as="trans('forms.business_case.maintenance_fund.label')"
            v-validate="'required'"
            :options="maintenanceFundList"
          />
          <form-error name="maintenance_fund_id"></form-error>
        </div>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.maintenance_fund_rationale.label')"
        prop="maintenance_fund_rationale"
        :required="form.maintenance_fund_id > 1">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.maintenance_fund_rationale.description')">
          </el-popover-wrap>
        </span>
        <el-input-wrap
          v-model="form.maintenance_fund_rationale"
          :data-vv-as="trans('forms.business_case.maintenance_fund_rationale.label')"
          name="maintenance_fund_rationale"
          v-validate="{ required: form.maintenance_fund_id > 1 }"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.salary_fund.label')"
        prop="salary_fund"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.salary_fund.description')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.salary_fund_id"
            :isLoading="isInfoLoading"
            name="salary_fund_id"
            :data-vv-as="trans('forms.business_case.salary_fund.label')"
            v-validate="'required'"
            :options="salaryFundList"
          />
          <form-error name="salary_fund_id"></form-error>
        </div>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.salary_fund_rationale.label')"
        prop="salary_fund_rationale"
        :required="form.salary_fund_id > 1">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.salary_fund_rationale.description')">
          </el-popover-wrap>
        </span>
        <el-input-wrap
          v-model="form.salary_fund_rationale"
          :data-vv-as="trans('forms.business_case.salary_fund_rationale.label')"
          name="salary_fund_rationale"
          v-validate="{ required: form.salary_fund_id > 1 }"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="internal_resources">
      <span slot="label" :class="{'is-error': errorTabs.includes('internal_resources') }">
        {{ trans('forms.business_case.tabs.internal_resources') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.internal_resources') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.internal_resources.label')"
        prop="internal_resources"
        :classes="['has-other']"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.internal_resources.description')"
            :help="trans('forms.business_case.internal_resources.help')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select-wrap
            v-model="form.internal_resources"
            :isLoading="isInfoLoading"
            name="internal_resources"
            :data-vv-as="trans('forms.business_case.internal_resources.label')"
            v-validate="{ rules: { required: !this.isInternalResourceOther} }"
            :options="internalResourceList"
            multiple
          />
          <form-error name="internal_resources"></form-error>
        </div>
        <el-input-other-wrap
          :data-vv-as="trans('entities.form.other')"
          name="internal_resource_other"
          v-model="form.internal_resource_other"
          v-validate="{ rules: { required: this.isInternalResourceOther} }"
          :isChecked.sync="isInternalResourceOther"
          maxlength="1250"
          type="textarea">
        </el-input-other-wrap>
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import FormSectionGroup from '../form-section-group';
  import ElTreeWrap from '../el-tree-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'business-case',

    components: { FormError, ElFormItemWrap, ElSelectWrap, ElInputWrap, ElInputOtherWrap, FormSectionGroup, ElTreeWrap, ElPopoverWrap },

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
        requestSourceList: [],
        isRequestSourceOther: false,
        potentialSolutionTypesList: [],
        isPotentialSolutionTypesOther: false,
        governmentPrioritiesList: [],
        departmentalBenefitTypeList: [],
        learnersBenefitTypeList: [],
        timeframeList: [],
        communitiesList: [],
        maintenanceFundList: [],
        salaryFundList: [],
        internalResourceList: [],
        isInternalResourceOther: false
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

    watch: {
      'form.expected_annual_participant_number': {
        immediate: true,
        handler(value) {
          // this handle the fact that we receive null from the server
          // and that the component converts null to 0,
          // which produces a form dirty: null !== 0
          value = value === null ? undefined : value;
          this.form.expected_annual_participant_number = value;
        }
      }
    },

    methods: {
      ...mapActions([
        'showMainLoading',
        'hideMainLoading'
      ]),

      formatData() {
        // make sure that the dropdowns only have the ids
        this.isInfoLoading = true;
        this.form.request_sources = _.map(this.form.request_sources, 'id');
        this.form.timeframe_id = _.get(this.form.timeframe, 'id');
        delete this.form.timeframe;
        this.form.maintenance_fund_id = _.get(this.form.maintenance_fund, 'id');
        delete this.form.maintenance_fund;
        this.form.communities = _.map(this.form.communities, 'id');
        this.form.government_priorities = _.map(this.form.government_priorities, 'id');
        this.form.potential_solution_types = _.map(this.form.potential_solution_types, 'id');
        this.form.internal_resources = _.map(this.form.internal_resources, 'id');

        this.isRequestSourceOther = !!this.form.request_source_other;
        this.isPotentialSolutionTypesOther = !!this.form.potential_solution_type_other;
        this.isInternalResourceOther = !!this.form.internal_resource_other;
        this.isInfoLoading = false;
      },

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetchLists() {
        await this.showMainLoading();
        this.isInfoLoading = true;
        let response = await axios.get('lists?include[]=request-source&include[]=potential-solution-type&include[]=government-priority&include[]=timeframe&include[]=community&include[]=departmental-benefit-type&include[]=learners-benefit-type&include[]=maintenance-fund&include[]=salary-fund&include[]=internal-resource');
        this.requestSourceList = response.data.data['request-source'];
        this.governmentPrioritiesList = response.data.data['government-priority'];
        this.potentialSolutionTypesList = response.data.data['potential-solution-type'];
        this.timeframeList = response.data.data['timeframe'];
        this.communitiesList = response.data.data['community'];
        this.departmentalBenefitTypeList = response.data.data['departmental-benefit-type'];
        this.learnersBenefitTypeList = response.data.data['learners-benefit-type'];
        this.maintenanceFundList = response.data.data['maintenance-fund'];
        this.salaryFundList = response.data.data['salary-fund'];
        this.internalResourceList = response.data.data['internal-resource'];
        this.isInfoLoading = false;
        await this.hideMainLoading();
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetchLists);
      EventBus.$off('FormEntity:formDataUpdate', this.formatData);
    },

    async created() {
      await this.showMainLoading();
      this.fetchLists();
      // load all the form fields with data passed in
      // create a new copy without reference so that we don't alter the original values
      this.form = _.cloneDeep(this.formData);
      this.formatData();
      await this.hideMainLoading();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetchLists);
      EventBus.$on('FormEntity:formDataUpdate', this.formatData);
    }
  };
</script>

<style lang="scss">

</style>
