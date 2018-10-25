<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="assessment">
      <span slot="label" :class="{'is-error': errorTabs.includes('assessment') }">
        {{ trans('forms.gate_one_approval.tabs.overall_assessment') }}
      </span>
      <h2>{{ trans('forms.gate_one_approval.tabs.overall_assessment') }}</h2>
      <el-form-item-wrap
        :label="trans('forms.gate_one_approval.assessment_date.label')"
        prop="assessment_date"
        required>
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.gate_one_approval.assessment_date.description')">
          </el-popover-wrap>
        </span>
        <el-date-picker
          v-model="form.assessment_date"
          v-validate="'required'"
          :data-vv-as="trans('forms.gate_one_approval.assessment_date.label')"
          name="assessment_date"
          :picker-options="assessment_date_options"
          type="date"
          value-format="yyyy-MM-dd">
        </el-date-picker>
        <form-error name="assessment_date"></form-error>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.gate_one_approval.is_entity_cancelled.label')"
        class="project-cancellation"
        prop="is_entity_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.gate_one_approval.is_entity_cancelled.description')">
          </el-popover-wrap>
        </span>
        <el-radio v-model="form.is_entity_cancelled" :label=false>{{ trans('forms.gate_one_approval.is_entity_cancelled.options.0') }}</el-radio>
        <el-radio v-model="form.is_entity_cancelled" :label=true>{{ trans('forms.gate_one_approval.is_entity_cancelled.options.1') }}</el-radio>
        <el-collapse-transition>
          <div v-show="form.is_entity_cancelled">
            <el-alert
              :closable="false"
              :title="trans('components.notice.message.cancel_project')"
              type="warning"
              show-icon>
            </el-alert>
          </div>
        </el-collapse-transition>
        <form-error name="is_entity_cancelled"></form-error>
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.gate_one_approval.entity_cancellation_rationale.label')"
        prop="entity_cancellation_rationale"
        :required="form.is_entity_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.gate_one_approval.entity_cancellation_rationale.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.gate_one_approval.entity_cancellation_rationale.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="form.entity_cancellation_rationale"
          :disabled="!form.is_entity_cancelled"
          v-validate="{ required: form.is_entity_cancelled }"
          :data-vv-as="trans('forms.gate_one_approval.entity_cancellation_rationale.label')"
          name="entity_cancellation_rationale"
          maxlength="2500"
          type="textarea">
        </input-wrap>
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane :data-name="item.assessed_process_form.replace(/-/g, '_')" v-for="(item, index) in form.assessments" :key="index">
      <span slot="label" :class="{'is-error': errorTabs.includes(item.assessed_process_form.replace(/-/g, '_')) }">
        {{ trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) }}
      </span>
      <h2>{{ trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) }}</h2>
      <el-form-item-wrap
        :label="trans('forms.gate_one_approval.process_form_decision_id.label')"
        prop="process_form_decision_id"
        :required="!form.is_entity_cancelled">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.gate_one_approval.process_form_decision_id.description')">
          </el-popover-wrap>
        </span>
        <el-select-wrap
          v-model="item.process_form_decision_id"
          :disabled="form.is_entity_cancelled"
          name="process_form_decision_id"
          :data-vv-as="trans('forms.gate_one_approval.process_form_decision_id.label')"
          v-validate="'required'"
          :options="processFormDecisionList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        :label="trans('forms.gate_one_approval.assessment_comments.label')"
        prop="assessment_comments"
        :required="item.process_form_decision_id === 2">
        <span slot="label-addons">
          <el-popover-wrap
            :description="trans('forms.gate_one_approval.assessment_comments.description')">
          </el-popover-wrap>
          <span class="instruction">
            {{ trans('forms.gate_one_approval.assessment_comments.instruction') }}
          </span>
        </span>
        <input-wrap
          v-model="item.comments"
          :disabled="form.is_entity_cancelled"
          v-validate="{ required: item.process_form_decision_id === 2 }"
          :data-vv-as="trans('forms.gate_one_approval.assessment_comments.label')"
          name="assessment_comments"
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
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  import ListsAPI from '@api/lists';

  export default {
    name: 'gate-one-approval',

    components: { FormError, ElFormItemWrap, ElSelectWrap, InputWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: Object,
      errorTabs: Array
    },

    data() {
      return {
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
      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      onProcessCancelledChange(isChecked) {
        // if checked, reset the form to null values
        if (isChecked) {
          this.form.entity_cancellation_rationale = null;
          this.form.assessments.forEach(assessment => {
            assessment.process_form_decision_id = null;
            assessment.comments = null;
          });
        } else {
          this.form.entity_cancellation_rationale = null;
        }
      },

      async fetch() {
        this.processFormDecisionList = await ListsAPI.getList('process-form-decision');
      },
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', this.fetch);
    },

    async created() {
      this.fetch();
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.fetch);
    }
  };
</script>

<style lang="scss">
  .project-cancellation {
    .el-radio {
      display: block;
      margin: 0 0 14px 0;
    }
  }
</style>
