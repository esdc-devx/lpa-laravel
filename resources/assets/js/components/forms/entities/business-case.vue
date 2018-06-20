<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('business-case') }">{{ trans('entities.form.business_drivers') }}</span>
      <!-- @todo: make sure the other input is registered as required-if -->
      <el-form-item for="requestSource" :class="['has-other', 'is-required', {'is-error': verrors.collect('requestSource').length }]" prop="requestSource">
        <span slot="label">
          {{ trans('entities.form.request_source') }}
          <el-popover-wrap
            :description="trans('components.popover.request_source_description')">
          </el-popover-wrap>
        </span>
        <el-select
          v-model="form.requestSource"
          name="requestSource"
          valueKey="name"
          v-validate="'required'"
          multiple>
          <el-option
            v-for="item in requestSource"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <el-input-other-wrap
          v-model="form.requestSourceOther"
          :isChecked.sync="form.isRequestSourceOther"
          maxlength="100">
        </el-input-other-wrap>
      </el-form-item>
      <el-form-item for="businessIssues" :class="['is-required', {'is-error': verrors.collect('businessIssues').length }]" prop="businessIssues">
        <span slot="label">
          {{ trans('entities.form.business_issues') }}
          <el-popover-wrap
            :description="trans('components.popover.business_issue_description')"
            :help="trans('components.popover.business_issue_help')">
          </el-popover-wrap>
        </span>
        <el-input-wrap
          v-model="form.businessIssues"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item>
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('proposal') }">{{ trans('entities.form.proposal') }}</span>
      <el-form-item for="learningResponseStrategy" :class="['is-required', {'is-error': verrors.collect('learningResponseStrategy').length }]" prop="learningResponseStrategy">
        <span slot="label">
          {{ trans('entities.form.learning_response_strategy') }}
          <el-popover-wrap
            :description="trans('components.popover.learning_response_strategy_description')">
          </el-popover-wrap>
        </span>
        <el-input-wrap
          v-model="form.learningResponseStrategy"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
      </el-form-item>
      <!-- @todo: make sure the other input is registered as required-if -->
      <el-form-item for="potentialSolutionTypes" :class="['has-other', 'is-required', {'is-error': verrors.collect('potentialSolutionTypes').length }]" prop="potentialSolutionTypes">
        <span slot="label">
          {{ trans('entities.form.potential_solution_types') }}
          <el-popover-wrap
            :description="trans('components.popover.potential_solution_types_description')"
            :help="trans('components.popover.potential_solution_types_help')">
          </el-popover-wrap>
        </span>
        <el-select
          v-model="form.potentialSolutionTypes"
          name="potentialSolutionTypes"
          valueKey="name"
          v-validate="'required'"
          multiple>
          <el-option
            v-for="item in potentialSolutionTypes"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <el-input-other-wrap
          v-model="form.potentialSolutionTypesOther"
          :isChecked.sync="form.ispotentialSolutionTypesOther"
          maxlength="1250"
          type="textarea">
        </el-input-other-wrap>
      </el-form-item>
      <el-form-item for="governmentPriorities" :class="['is-required', {'is-error': verrors.collect('governmentPriorities').length }]" prop="governmentPriorities">
        <span slot="label">
          {{ trans('entities.form.government_priorities') }}
          <el-popover-wrap
            :description="trans('components.popover.government_priorities_description')">
          </el-popover-wrap>
        </span>
        <el-select
          v-model="form.governmentPriorities"
          name="governmentPriorities"
          valueKey="name"
          v-validate="'required'"
          multiple>
          <el-option
            v-for="item in governmentPriorities"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item for="isRequiredTraining" :class="['is-required', {'is-error': verrors.collect('isRequiredTraining').length }]" prop="isRequiredTraining">
        <span slot="label">
          {{ trans('entities.form.required_training') }}
          <el-popover-wrap
            :description="trans('components.popover.required_training_description')">
          </el-popover-wrap>
        </span>
        <el-radio-group v-model="form.isRequiredTraining">
          <el-radio-button :label="trans('base.actions.yes')"></el-radio-button>
          <el-radio-button :label="trans('base.actions.no')"></el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'business-case',

    components: { ElInputWrap, ElInputOtherWrap, ElPopoverWrap },

    data() {
      return {
        errorTabs: [],
        requestSource: [],
        potentialSolutionTypes: [],
        governmentPriorities: [],
        form: {
          learningResponseStrategy: '',
          requestSource: [],
          requestSourceOther: '',
          isRequestSourceOther: false,
          ispotentialSolutionTypesOther: false,
          businessIssues: '',
          potentialSolutionTypes: [],
          potentialSolutionTypesOther: '',
          governmentPriorities: [],
          isRequiredTraining: null
        }
      }
    },

    methods: {
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetchFormData() {
        let response = await axios.get('lists?include[]=request-source&include[]=potential-solution-type&include[]=government-priority');
        this.requestSource = response.data.data['request-source'];
        this.governmentPriorities = response.data.data['government-priority'];
        this.potentialSolutionTypes = response.data.data['potential-solution-type'];
      }
    },

    mounted() {
      this.fetchFormData();
    }
  };
</script>

<style lang="scss">

</style>
