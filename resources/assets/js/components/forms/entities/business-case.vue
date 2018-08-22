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
        <el-select-other-wrap
          :modelSelect.sync="form.potential_solution_types"
          nameSelect="potential_solution_types"
          :dataVVasSelect="trans('forms.business_case.potential_solution_types.label')"
          :validateSelect="{ required: !this.isPotentialSolutionTypesOther }"
          :options="potentialSolutionTypesList"
          multiple
          sorted

          :modelOther.sync="form.potential_solution_type_other"
          nameOther="potential_solution_type_other"
          :dataVVasOther="trans('forms.business_case.potential_solution_type_other.label')"
          :validateOther="{ required: this.isPotentialSolutionTypesOther }"
          :isChecked.sync="isPotentialSolutionTypesOther"
          maxlength="1250"
          type="textarea"
        />
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
        <el-select-wrap
          v-model="form.government_priorities"
          name="government_priorities"
          :data-vv-as="trans('forms.business_case.government_priorities.label')"
          v-validate="'required'"
          :options="governmentPrioritiesList"
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
        <el-select-wrap
          v-model="form.timeframe_id"
          name="timeframe_id"
          :data-vv-as="trans('forms.business_case.timeframe.label')"
          v-validate="'required'"
          :options="timeframeList"
        />
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
        <input-wrap
          v-model="form.timeframe_rationale"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.timeframe_rationale.label')"
          name="timeframe_rationale"
          maxlength="1250"
          type="textarea">
        </input-wrap>
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
          <span class="instruction">
            {{ trans('forms.business_case.communities.instruction') }}
          </span>
        </span>
        <el-tree-wrap
          name="communities"
          v-model="form.communities"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case.communities.label')"
          :data="communitiesList"
          labelKey="name"
          sorted>
        </el-tree-wrap>
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
    </el-tab-pane>

    <el-tab-pane data-name="departmental_benefit">
      <span slot="label" :class="{'is-error': errorTabs.includes('departmental_benefit') }">
        {{ $tc('forms.business_case.tabs.departmental_benefit', 2) }}
      </span>
      <form-section-group
        v-model="form.departmental_benefits"
        entityForm="business-case"
        entitySection="departmental-benefit"
        :data="{
          departmentalBenefitTypeList
        }"
      />
    </el-tab-pane>

    <el-tab-pane data-name="learners_benefit">
      <span slot="label" :class="{'is-error': errorTabs.includes('learners_benefit') }">
        {{ $tc('forms.business_case.tabs.learners_benefit', 2) }}
      </span>
      <form-section-group
        v-model="form.learners_benefits"
        entityForm="business-case"
        entitySection="learners-benefit"
        :data="{
          learnersBenefitTypeList
        }"
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
        <input-wrap
          v-model="form.cost_center"
          v-validate="{ required: true, regex: /[A-Z][0-9]{5,5}/ }"
          :data-vv-as="trans('forms.business_case.cost_center.label')"
          name="cost_center"
          :placeholder="trans('forms.business_case.cost_center.hint')"
          v-mask="'A#####'">
        </input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.maintenance_fund.label')"
        prop="maintenance_fund_id"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.maintenance_fund.description')"
            :help="trans('forms.business_case.maintenance_fund.help')">
          </el-popover-wrap>
        </span>
        <el-select-wrap
          v-model="form.maintenance_fund_id"
          name="maintenance_fund_id"
          :data-vv-as="trans('forms.business_case.maintenance_fund.label')"
          v-validate="'required'"
          :options="maintenanceFundList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.maintenance_fund_rationale.label')"
        prop="maintenance_fund_rationale"
        :required="form.maintenance_fund_id > 1">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.maintenance_fund_rationale.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.maintenance_fund_rationale.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.maintenance_fund_rationale"
          v-validate="{ required: form.maintenance_fund_id > 1 }"
          :data-vv-as="trans('forms.business_case.maintenance_fund_rationale.label')"
          name="maintenance_fund_rationale"
          maxlength="1250"
          type="textarea">
        </input-wrap>
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
        <el-select-wrap
          v-model="form.salary_fund_id"
          name="salary_fund_id"
          :data-vv-as="trans('forms.business_case.salary_fund.label')"
          v-validate="'required'"
          :options="salaryFundList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case.salary_fund_rationale.label')"
        prop="salary_fund_rationale"
        :required="form.salary_fund_id > 1">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.salary_fund_rationale.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.salary_fund_rationale.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.salary_fund_rationale"
          v-validate="{ required: form.salary_fund_id > 1 }"
          :data-vv-as="trans('forms.business_case.salary_fund_rationale.label')"
          name="salary_fund_rationale"
          maxlength="1250"
          type="textarea">
        </input-wrap>
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
          <span class="instruction">
            {{ trans('forms.business_case.internal_resources.instruction') }}
          </span>
        </span>
        <el-select-other-wrap
          :modelSelect.sync="form.internal_resources"
          nameSelect="internal_resources"
          :dataVVasSelect="trans('forms.business_case.internal_resources.label')"
          :validateSelect="{ required: !this.isInternalResourceOther }"
          :options="internalResourceList"
          multiple
          sorted

          :modelOther.sync="form.internal_resource_other"
          nameOther="internal_resource_other"
          :dataVVasOther="trans('forms.business_case.internal_resource_other.label')"
          :validateOther="{ required: this.isInternalResourceOther }"
          :isChecked.sync="isInternalResourceOther"
          maxlength="1250"
          type="textarea"
        />
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
          riskTypeList,
          impactLevelList,
          probabilityLevelList
        }"
      />
    </el-tab-pane>

    <el-tab-pane data-name="comment">
      <span slot="label" :class="{'is-error': errorTabs.includes('comment') }">
        {{ trans('forms.business_case.tabs.comment') }}
      </span>
      <h2>{{ trans('forms.business_case.tabs.comment') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case.comment.label')"
        prop="comment">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.comment.description')">
          </el-popover-wrap>
        </span>
        <input-wrap
          v-model="form.comment"
          v-validate="''"
          :data-vv-as="trans('forms.business_case.comment.label')"
          name="comment"
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
        isInternalResourceOther: false,
        riskTypeList: [],
        impactLevelList: [],
        probabilityLevelList: []
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

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetchLists() {
        await this.showMainLoading();
        let response = await axios.get('lists?include[]=request-source&include[]=potential-solution-type&include[]=government-priority&include[]=timeframe&include[]=community&include[]=departmental-benefit-type&include[]=learners-benefit-type&include[]=maintenance-fund&include[]=salary-fund&include[]=internal-resource&include[]=risk-type&include[]=risk-impact-level&include[]=risk-probability-level');
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
        this.riskTypeList = response.data.data['risk-type'];
        this.impactLevelList = response.data.data['risk-impact-level'];
        this.probabilityLevelList = response.data.data['risk-probability-level'];
        await this.hideMainLoading();
      },

      bindCheckboxes() {
        this.isRequestSourceOther = !!this.form.request_source_other;
        this.isPotentialSolutionTypesOther = !!this.form.potential_solution_type_other;
        this.isInternalResourceOther = !!this.form.internal_resource_other;
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetchLists);
      EventBus.$off('FormEntity:discardChanges', this.bindCheckboxes);
    },

    async created() {
      await this.showMainLoading();
      this.fetchLists();
      // load all the form fields with data passed in
      // create a new copy without reference so that we don't alter the original values
      this.form = _.cloneDeep(this.formData);
      this.bindCheckboxes();
      await this.hideMainLoading();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetchLists);
      EventBus.$on('FormEntity:discardChanges', this.bindCheckboxes);
    }
  };
</script>

<style lang="scss">

</style>
