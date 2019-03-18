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
      <template v-if="form.allow_entity_cancellation">
        <el-form-item-wrap
          class="entity-cancellation"
          :contextPath="`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled`"
          prop="is_entity_cancelled"
        >
          <el-radio-group
            v-model="form.is_entity_cancelled"
            v-validate="''"
            name="is_entity_cancelled"
            @change="onEntityCancelledChange"
          >
            <el-radio :label=false>{{ trans(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled.options.false`) }}</el-radio>
            <el-radio :label=true>{{ trans(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled.options.true`) }}</el-radio>
          </el-radio-group>
          <el-collapse-transition>
            <div v-show="form.is_entity_cancelled">
              <el-alert
                :closable="false"
                :title="trans(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.cancel_entity`)"
                type="warning"
                show-icon
              />
            </div>
          </el-collapse-transition>
          <form-error name="is_entity_cancelled"/>
        </el-form-item-wrap>
        <el-form-item-wrap
          prop="entity_cancellation_rationale"
          :contextPath="`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.entity_cancellation_rationale`"
          :required="form.is_entity_cancelled"
        >
          <input-wrap
            v-model="form.entity_cancellation_rationale"
            :disabled="!form.is_entity_cancelled"
            v-validate="{ required: form.is_entity_cancelled }"
            :data-vv-as="trans(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.entity_cancellation_rationale.label`)"
            name="entity_cancellation_rationale"
            maxlength="2500"
            type="textarea"
          />
        </el-form-item-wrap>
      </template>
    </el-tab-pane>
    <el-tab-pane :data-name="item.assessed_process_form.replace(/-/g, '_')" v-for="(item, index) in form.assessments" :key="index">
      <span slot="label" :class="{'is-error': errorTabs.includes(item.assessed_process_form.replace(/-/g, '_')) }">
        {{ getSectionTitle(item) }}
      </span>
      <h3>{{ getSectionTitle(item) }}</h3>
      <el-form-item-wrap
        prop="process_form_decision_id"
        :description="trans('forms.form_assessment.process_form_decision_id.description', { assessed_form_title: getSectionTitle(item) })"
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
        :description="trans('forms.form_assessment.assessment_comments.description', { assessed_form_title: getSectionTitle(item) })"
        :required="isAssessmentCommentsFieldRequired(item)"
      >
        <input-wrap
          v-model="item.comments"
          :disabled="form.is_entity_cancelled"
          v-validate="{ required: isAssessmentCommentsFieldRequired(item) }"
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
  import Form from './form.vue';

  const loadData = async () => {
    await store.dispatch('lists/loadList', 'process-form-decision');
  };

  export default {
    name: 'assessment',

    extends: Form,

    data() {
      return {
        assessment_date_options: {
          disabledDate(time) {
            return time.getTime() < new Date('2000-01-01') || time.getTime() > new Date();
          }
        },
        snakeCase: _.snakeCase
      }
    },

    computed: {
      processFormDecisionList() {
        return this.getList('process-form-decision');
      }
    },

    methods: {
      loadData,

      getSectionTitle(item) {
        return this.trans(`forms.${item.assessed_process_form.replace(/-/g, '_')}.form_title`);
      },

      isAssessmentCommentsFieldRequired(item) {
        const processFormDecision = _.find(this.processFormDecisionList, ['id', item.process_form_decision_id]);
        return processFormDecision ? processFormDecision.name_key === 'rejected' : false;
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

    mounted() {
      this.$on('update:value', index => {
        // emit straight to the form entity component (e.g: gate-one-approval)
        this.$parent.$emit('update:value', index);
      });
      // emit straight to the form entity component (e.g: gate-one-approval)
      this.$parent.$emit('mounted');
    }
  };
</script>

<style lang="scss">
  .entity-cancellation {
    .el-radio {
      display: block;
      margin: 0 0 14px 0;
    }
  }
  .el-alert__content {
    line-height: 1.3em;
  }
</style>
