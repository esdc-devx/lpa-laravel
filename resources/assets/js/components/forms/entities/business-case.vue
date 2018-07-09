<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('business-case') }">{{ trans('forms.business_case.tabs.business_drivers') }}</span>
      <!-- @todo: make sure the other input is registered as required-if -->
      <el-form-item for="request_sources" :class="['has-other', 'is-required', {'is-error': verrors.collect('request_sources').length }]" prop="request_sources">
        <span slot="label">
          {{ trans('forms.business_case.label.request_sources') }}
          <el-popover-wrap
            :description="trans('components.popover.description.request_sources')">
          </el-popover-wrap>
        </span>
        <el-select
          v-model="form.request_sources"
          v-loading="isInfoLoading"
          :disabled="isInfoLoading"
          element-loading-spinner="el-icon-loading"
          name="request_sources"
          value-key="name"
          v-validate="'required'"
          multiple>
          <el-option
            v-for="item in requestSourceServer"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <el-input-other-wrap
          name="request_source_other"
          v-model="form.request_source_other"
          v-validate="{ rules: { required: this.isRequestSourceOther} }"
          :isChecked.sync="isRequestSourceOther"
          maxlength="100">
        </el-input-other-wrap>
      </el-form-item>
      <el-form-item for="business_issue" :class="['is-required', {'is-error': verrors.collect('business_issue').length }]" prop="business_issue">
        <span slot="label">
          {{ trans('forms.business_case.label.business_issue') }}
          <el-popover-wrap
            :description="trans('components.popover.description.business_issue')"
            :help="trans('components.popover.help.business_issue')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.instruction.business_issue') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.business_issue"
          name="business_issue"
          v-validate="'required'"
          maxlength="1250"
          type="textarea">
        </el-input-wrap>
      </el-form-item>
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label" :class="{'is-error': errorTabs.includes('proposal') }">{{ trans('forms.business_case.tabs.proposal') }}</span>
      <el-form-item for="learning_response_strategy" :class="['is-required', {'is-error': verrors.collect('learning_response_strategy').length }]" prop="learning_response_strategy">
        <span slot="label">
          {{ trans('forms.business_case.label.learning_response_strategy') }}
          <el-popover-wrap
            :description="trans('components.popover.description.learning_response_strategy')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.instruction.learning_response_strategy') }}
          </span>
        </span>
        <el-input-wrap
          v-model="form.learning_response_strategy"
          name="learning_response_strategy"
          v-validate="'required'"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
      </el-form-item>
      <!-- @todo: make sure the other input is registered as required-if -->
      <el-form-item for="potential_solution_types" :class="['has-other', 'is-required', {'is-error': verrors.collect('potential_solution_types').length }]" prop="potential_solution_types">
        <span slot="label">
          {{ trans('forms.business_case.label.potential_solution_types') }}
          <el-popover-wrap
            :description="trans('components.popover.description.potential_solution_types')"
            :help="trans('components.popover.help.potential_solution_types')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.instruction.potential_solution_types') }}
          </span>
        </span>
        <el-select
          v-model="form.potential_solution_types"
          v-loading="isInfoLoading"
          :disabled="isInfoLoading"
          element-loading-spinner="el-icon-loading"
          name="potential_solution_types"
          valueKey="name"
          v-validate="'required'"
          multiple>
          <el-option
            v-for="item in potentialSolutionTypesServer"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <el-input-other-wrap
          name="potential_solution_type_other"
          v-model="form.potential_solution_type_other"
          :isChecked.sync="isPotentialSolutionTypesOther"
          v-validate="{ rules: { required: this.isPotentialSolutionTypesOther} }"
          maxlength="1250"
          type="textarea">
        </el-input-other-wrap>
      </el-form-item>
      <el-form-item for="government_priorities" :class="['is-required', {'is-error': verrors.collect('government_priorities').length }]" prop="government_priorities">
        <span slot="label">
          {{ trans('forms.business_case.label.government_priorities') }}
          <el-popover-wrap
            :description="trans('components.popover.description.government_priorities')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.instruction.government_priorities') }}
          </span>
        </span>
        <el-select
          v-model="form.government_priorities"
          v-loading="isInfoLoading"
          :disabled="isInfoLoading"
          element-loading-spinner="el-icon-loading"
          name="government_priorities"
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
      </el-form-item>
      <el-form-item for="is_required_training" :class="['is-required', {'is-error': verrors.collect('is_required_training').length }]" prop="is_required_training">
        <span slot="label">
          {{ trans('forms.business_case.label.is_required_training') }}
          <el-popover-wrap
            :description="trans('components.popover.description.is_required_training')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.business_case.instruction.is_required_training') }}
          </span>
        </span>
        <el-radio-group
          v-model="form.is_required_training"
          name="is_required_training"
          v-validate="'required'">
            <el-radio-button :label="0">{{ trans('base.actions.yes') }}</el-radio-button>
            <el-radio-button :label="1">{{ trans('base.actions.no') }}</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapActions } from 'vuex';

  import EventBus from '../../../event-bus.js';

  import ElInputWrap from '../el-input-wrap';
  import ElInputOtherWrap from '../el-input-other-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'business-case',

    components: { ElInputWrap, ElInputOtherWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: ['formData'],

    data() {
      return {
        errorTabs: [],
        isInfoLoading: true,
        requestSourceServer: [],
        isRequestSourceOther: false,
        potentialSolutionTypesServer: [],
        isPotentialSolutionTypesOther: false,
        governmentPrioritiesServer: [],
        form: []
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading'
      }),

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      async fetchLists() {
        await this.showMainLoading();
        this.isInfoLoading = true;
        let response = await axios.get('lists?include[]=request-source&include[]=potential-solution-type&include[]=government-priority');
        this.requestSourceServer = response.data.data['request-source'];
        this.governmentPrioritiesServer = response.data.data['government-priority'];
        this.potentialSolutionTypesServer = response.data.data['potential-solution-type'];
        this.isInfoLoading = false;
        await this.hideMainLoading();
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetchLists);
    },

    async created() {
      await this.showMainLoading();
      await this.fetchLists();
      // load all the form fields with data passed in
      // create a new copy without reference so that we don't alter the original values
      this.form = Object.assign({}, this.formData);
      // make the checkboxes react
      // based on the value of its correcponding field
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
