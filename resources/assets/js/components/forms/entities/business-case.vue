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
          <el-select
            v-model="form.request_sources"
            v-loading="isInfoLoading"
            :disabled="isInfoLoading"
            element-loading-spinner="el-icon-loading"
            name="request_sources"
            :data-vv-as="trans('forms.business_case.request_sources.label')"
            value-key="name"
            v-validate="{ rules: { required: !this.isRequestSourceOther} }"
            :class="{ 'is-error': verrors.has('request_sources') }"
            multiple>
            <el-option
              v-for="item in requestSourceServer"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
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
          <el-select
            v-model="form.potential_solution_types"
            v-loading="isInfoLoading"
            :disabled="isInfoLoading"
            element-loading-spinner="el-icon-loading"
            :data-vv-as="trans('forms.business_case.potential_solution_types.label')"
            name="potential_solution_types"
            :class="{ 'is-error': verrors.has('potential_solution_types') }"
            valueKey="name"
            v-validate="{ rules: { required: !this.isPotentialSolutionTypesOther} }"
            multiple>
            <el-option
              v-for="item in potentialSolutionTypesServer"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
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
          <el-select
            v-model="form.government_priorities"
            v-loading="isInfoLoading"
            :disabled="isInfoLoading"
            element-loading-spinner="el-icon-loading"
            :data-vv-as="trans('forms.business_case.government_priorities.label')"
            name="government_priorities"
            :class="{ 'is-error': verrors.has('government_priorities') }"
            valueKey="name"
            v-validate="'required'"
            multiple>
            <el-option
              v-for="item in governmentPrioritiesServer"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
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
        prop="timeframe"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case.timeframe.description')"
            :help="trans('forms.business_case.timeframe.help')">
          </el-popover-wrap>
        </span>
        <div class="wrap-with-errors">
          <el-select
            v-model="form.timeframe"
            v-loading="isInfoLoading"
            :disabled="isInfoLoading"
            element-loading-spinner="el-icon-loading"
            name="timeframe"
            :data-vv-as="trans('forms.business_case.timeframe.label')"
            value-key="name"
            v-validate="{ rules: { required: !this.isRequestSourceOther} }"
            :class="{ 'is-error': verrors.has('timeframe') }">
            <el-option
              v-for="item in timeframeServer"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
          <form-error name="timeframe"></form-error>
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
            :data="communitiesServer"
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
          v-validate="'required'"
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
          departmentalBenefitTypeServer
        }"
        :min="1"
        :isLoading="isInfoLoading"
      />
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import FormSectionGroup from '../form-section-group';
  import ElTreeWrap from '../el-tree-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'business-case',

    components: { FormError, ElFormItemWrap, ElInputWrap, ElInputOtherWrap, FormSectionGroup, ElTreeWrap, ElPopoverWrap },

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
        requestSourceServer: [],
        isRequestSourceOther: false,
        potentialSolutionTypesServer: [],
        isPotentialSolutionTypesOther: false,
        governmentPrioritiesServer: [],
        departmentalBenefitTypeServer: [],
        timeframeServer: [],
        communitiesServer: [],
        innerFormData: this.formData
      }
    },

    computed: {
      form: {
        get() {
          return this.innerFormData;
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
        this.isInfoLoading = true;
        let response = await axios.get('lists?include[]=request-source&include[]=potential-solution-type&include[]=government-priority&include[]=timeframe&include[]=community&include[]=departmental-benefit-type');
        this.requestSourceServer = response.data.data['request-source'];
        this.governmentPrioritiesServer = response.data.data['government-priority'];
        this.potentialSolutionTypesServer = response.data.data['potential-solution-type'];
        this.timeframeServer = response.data.data['timeframe'];
        this.communitiesServer = response.data.data['community'];
        this.departmentalBenefitTypeServer = response.data.data['departmental-benefit-type'];
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
      // make the checkboxes react
      // based on the value of its corresponding field
      this.isRequestSourceOther = !!this.form.request_source_other;
      this.isPotentialSolutionTypesOther = !!this.form.potential_solution_type_other;
      await this.hideMainLoading();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetchLists);
    }
  };
</script>

<style lang="scss">

</style>
