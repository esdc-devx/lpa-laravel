<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="assessment">
      <span slot="label" :class="{'is-error': errorTabs.includes('assessment') }">
        {{ trans('forms.business_case_assessment.tabs.assessment') }}
      </span>
      <h2>{{ trans('forms.business_case_assessment.tabs.assessment') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case_assessment.is_process_cancelled.label')"
        prop="is_process_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case_assessment.is_process_cancelled.description')">
          </el-popover-wrap>
        </span>
        <el-checkbox
          v-model="form.is_process_cancelled"
          @change="onProcessCancelledChange">
            {{ trans('forms.business_case_assessment.is_process_cancelled.option') }}
          </el-checkbox>
        <el-collapse-transition>
          <div v-show="form.is_process_cancelled">
            <el-alert
              :closable="false"
              :title="trans('components.notice.message.cancel_project')"
              type="warning"
              show-icon>
            </el-alert>
          </div>
        </el-collapse-transition>
        <form-error name="is_process_cancelled"></form-error>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case_assessment.process_cancellation_rationale.label')"
        prop="process_cancellation_rationale"
        :required="form.is_process_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case_assessment.process_cancellation_rationale.description')">
          </el-popover-wrap>
        </span>
        <el-input-wrap
          v-model="form.process_cancellation_rationale"
          :disabled="!form.is_process_cancelled"
          v-validate="{ required: !!form.is_process_cancelled }"
          :data-vv-as="trans('forms.business_case_assessment.process_cancellation_rationale.label')"
          name="process_cancellation_rationale"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case_assessment.assessment_date.label')"
        prop="assessment_date"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case_assessment.assessment_date.description')">
          </el-popover-wrap>
        </span>
        <el-date-picker
          v-model="form.assessment_date"
          v-validate="'required'"
          :data-vv-as="trans('forms.business_case_assessment.assessment_date.label')"
          name="assessment_date"
          :picker-options="assessment_date_options"
          type="date"
          value-format="yyyy-MM-dd">
        </el-date-picker>
        <form-error name="assessment_date"></form-error>
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane :data-name="item.assessed_process_form.replace('-', '_')" v-for="(item, index) in form.assessments" :key="index">
      <span slot="label" :class="{'is-error': errorTabs.includes(item.assessed_process_form.replace('-', '_')) }">
        {{ trans(`forms.${item.assessed_process_form.replace('-', '_')}.title`) }}
      </span>
      <h2>{{ trans(`forms.${item.assessed_process_form.replace('-', '_')}.title`) }}</h2>
      <el-form-item-wrap
        :label="trans('forms.business_case_assessment.process_form_decision_id.label')"
        prop="process_form_decision_id"
        :required="!form.is_process_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case_assessment.process_form_decision_id.description')">
          </el-popover-wrap>
        </span>
        <el-select-wrap
          v-model="item.process_form_decision_id"
          :disabled="form.is_process_cancelled"
          :isLoading="isInfoLoading"
          name="process_form_decision_id"
          :data-vv-as="trans('forms.business_case_assessment.process_form_decision_id.label')"
          v-validate="'required'"
          :options="processFormDecisionList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.business_case_assessment.assessment_comment.label')"
        prop="assessment_comment"
        :required="item.process_form_decision_id === 2">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.business_case_assessment.assessment_comment.description')">
          </el-popover-wrap>
        </span>
        <span class="instruction">
          {{ trans('forms.business_case_assessment.assessment_comment.instruction') }}
        </span>
        <el-input-wrap
          v-model="item.comment"
          :disabled="form.is_process_cancelled"
          v-validate="{ required: item.process_form_decision_id === 2 }"
          :data-vv-as="trans('forms.business_case_assessment.assessment_comment.label')"
          name="assessment_comment"
          maxlength="2500"
          type="textarea">
        </el-input-wrap>
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
  import ElPopoverWrap from '../../el-popover-wrap';

  export default {
    name: 'business-case-assessment',

    components: { FormError, ElFormItemWrap, ElSelectWrap, ElInputWrap, ElPopoverWrap },

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
        processFormDecisionList: [],
        assessment_date_options: {
          disabledDate(time) {
            return time.getTime() < new Date('2000-01-01') || time.getTime() > new Date();
          }
        }
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
      ...mapActions([
        'showMainLoading',
        'hideMainLoading'
      ]),

      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      onProcessCancelledChange(isChecked) {
        // if checked, reset the form to null values
        if (isChecked) {
          this.form.process_cancellation_rationale = null;
          this.form.assessments.forEach(assessment => {
            assessment.process_form_decision_id = null;
            assessment.comment = null;
          });
        } else {
          this.form.process_cancellation_rationale = null;
        }
      },

      async fetchLists() {
        await this.showMainLoading();
        this.isInfoLoading = true;
        let response = await axios.get('lists?include[]=process-form-decision');
        this.processFormDecisionList = response.data.data['process-form-decision'];
        this.isInfoLoading = false;
        await this.hideMainLoading();
      },
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
      await this.hideMainLoading();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetchLists);
    }
  };
</script>

<style lang="scss">

</style>
