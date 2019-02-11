<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="assessment">
      <span slot="label" :class="{'is-error': errorTabs.includes('assessment') }">
        {{ trans('forms.form_assessment.tabs.overall_assessment') }}
      </span>
      <h3>{{ trans('forms.form_assessment.tabs.overall_assessment') }}</h3>
      <el-form-item-wrap
        prop="assessment_date"
        contextPath="forms.form_assessment.assessment_date"
        required
      >
        <el-date-picker
          v-model="form.assessment_date"
          v-validate="'required'"
          :data-vv-as="trans('forms.form_assessment.assessment_date.label')"
          name="assessment_date"
          :picker-options="assessment_date_options"
          type="date"
          value-format="yyyy-MM-dd"
        />
        <form-error name="assessment_date"/>
      </el-form-item-wrap>
      <el-form-item-wrap
        class="project-cancellation"
        contextPath="forms.gate_one_approval.is_entity_cancelled"
        prop="is_entity_cancelled"
      >
        <el-radio-group
          v-model="form.is_entity_cancelled"
          v-validate="''"
          name="is_entity_cancelled"
          @change="onEntityCancelledChange"
        >
          <el-radio :label=false>{{ trans('forms.gate_one_approval.is_entity_cancelled.options.false') }}</el-radio>
          <el-radio :label=true>{{ trans('forms.gate_one_approval.is_entity_cancelled.options.true') }}</el-radio>
        </el-radio-group>
        <el-collapse-transition>
          <div v-show="form.is_entity_cancelled">
            <el-alert
              :closable="false"
              :title="trans('components.notice.message.cancel_project')"
              type="warning"
              show-icon
            />
          </div>
        </el-collapse-transition>
        <form-error name="is_entity_cancelled"/>
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="entity_cancellation_rationale"
        contextPath="forms.gate_one_approval.entity_cancellation_rationale"
        :required="form.is_entity_cancelled">
        <input-wrap
          v-model="form.entity_cancellation_rationale"
          :disabled="!form.is_entity_cancelled"
          v-validate="{ required: form.is_entity_cancelled }"
          :data-vv-as="trans('forms.gate_one_approval.entity_cancellation_rationale.label')"
          name="entity_cancellation_rationale"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane :data-name="item.assessed_process_form.replace(/-/g, '_')" v-for="(item, index) in form.assessments" :key="index">
      <span slot="label" :class="{'is-error': errorTabs.includes(item.assessed_process_form.replace(/-/g, '_')) }">
        {{ trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) }}
      </span>
      <h3>{{ trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) }}</h3>
      <el-form-item-wrap
        prop="process_form_decision_id"
        :description="trans('forms.form_assessment.process_form_decision_id.description', { assessed_form_title: trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) })"
        contextPath="forms.form_assessment.process_form_decision_id"
        :required="!form.is_entity_cancelled"
      >
        <el-select-wrap
          v-model="item.process_form_decision_id"
          :disabled="form.is_entity_cancelled"
          :name="'process_form_decision_id.' + index"
          :data-vv-as="trans('forms.form_assessment.process_form_decision_id.label')"
          v-validate="'required'"
          :options="processFormDecisionList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="assessment_comments"
        contextPath="forms.form_assessment.assessment_comments"
        :description="trans('forms.form_assessment.assessment_comments.description', { assessed_form_title: trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.title`) })"
        :required="item.process_form_decision_id === 2"
      >
        <input-wrap
          v-model="item.comments"
          :disabled="form.is_entity_cancelled"
          v-validate="{ required: item.process_form_decision_id === 2 }"
          :data-vv-as="trans('forms.form_assessment.assessment_comments.label')"
          :name="'assessment_comments.' + index"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapGetters } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '../error.vue';

  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';
  import ElPopoverWrap from '../../el-popover-wrap';

  const loadData = async () => {
    await store.dispatch('lists/loadList', 'process-form-decision');
  };

  export default {
    name: 'gate-one-approval',

    components: { FormError, ElFormItemWrap, ElSelectWrap, InputWrap, ElPopoverWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      formData: {
        type: Object
      },
      errorTabs: {
        type: Array
      }
    },

    data() {
      return {
        assessment_date_options: {
          disabledDate(time) {
            return time.getTime() < new Date('2000-01-01') || time.getTime() > new Date();
          }
        }
      }
    },

    computed: {
      ...mapGetters('lists', {
        getList: 'list'
      }),

      form: {
        get() {
          return this.formData;
        },
        set(data) {
          this.$emit('update:formData', data);
        }
      },

      processFormDecisionList() {
        return this.getList('process-form-decision');
      }
    },

    methods: {
      // used in order to sync the tab index with the parent
      onTabClick(tab, e) {
        this.$emit('update:value', tab.index);
      },

      onEntityCancelledChange(isChecked) {
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
      }
    },

    beforeDestroy() {
      EventBus.$off('Store:languageUpdate', loadData);
    },

    beforeCreate() {
      loadData();
    },

    mounted() {
      this.$emit('mounted');
      EventBus.$on('Store:languageUpdate', loadData);
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
  .el-alert__content {
    line-height: 1.3em;
  }
</style>
