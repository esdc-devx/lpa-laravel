<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="description">
      <span slot="label" :class="{'is-error': errorTabs.includes('description') }">
        {{ trans('forms.design.tabs.description') }}
      </span>
      <h3>{{ trans('forms.design.tabs.description') }}</h3>
      <el-form-item-wrap
        prop="learning_product_code_id"
        contextPath="forms.design.learning_product_code"
        required
      >
        <learning-product-search
          name="learning_product_code_id"
          :validate="'required'"
          :dataVvAs="trans('forms.design.learning_product_code.label')"
          :model.sync="form.learning_product_code_id"
          :codes="codes"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="sequence"
        contextPath="forms.design.sequence"
        required
      >
        <input-wrap
          v-model="form.sequence"
          name="sequence"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.design.sequence.label')"
          :min="1"
          :max="99"
          type="number"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="version"
        contextPath="forms.design.version"
        required
      >
        <input-wrap
          v-model="form.version"
          name="version"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.design.version.label')"
          :min="1"
          :max="99"
          type="number"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="description"
        contextPath="forms.design.description"
        required
      >
        <input-wrap
          v-model="form.description"
          name="description"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.description.label')"
          maxlength="1000"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="learning_objectives"
        contextPath="forms.design.learning_objectives"
        required
      >
        <input-wrap
          v-model="form.learning_objectives"
          name="learning_objectives"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.learning_objectives.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="target_audience_description"
        contextPath="forms.design.target_audience_description"
        required
      >
        <input-wrap
          v-model="form.target_audience_description"
          name="target_audience_description"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.target_audience_description.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="outcome_types"
        contextPath="forms.design.outcome_types"
        required
      >
        <el-select-wrap
          v-model="form.outcome_types"
          name="outcome_types"
          :data-vv-as="trans('forms.design.outcome_types.label')"
          v-validate="'required'"
          :options="outcomeTypesList"
          multiple
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="is_required_training"
        contextPath="forms.design.is_required_training"
        required
      >
        <yes-no
          v-model="form.is_required_training"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.is_required_training.label')"
          name="is_required_training"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="total_duration"
        contextPath="forms.design.total_duration"
        required
        class="total_duration"
      >
        <duration
          isRequired
          v-model="form.total_duration"
          :hoursMaxValue="225"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="specifications">
      <span slot="label" :class="{'is-error': errorTabs.includes('specifications') }">
        {{ trans('forms.design.tabs.specifications') }}
      </span>
      <h3>{{ trans('forms.design.tabs.specifications') }}</h3>
      <el-form-item-wrap
        v-if="learningProduct.type.name_key == 'course' && learningProduct.sub_type.name_key === 'instructor-led'"
        prop="possible_offering_types"
        contextPath="forms.design.possible_offering_types"
        required
      >
        <el-select-wrap
          v-model="form.possible_offering_types"
          name="possible_offering_types"
          :data-vv-as="trans('forms.design.possible_offering_types.label')"
          v-validate="'required'"
          :options="possibleOfferingTypesList"
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="registration_mode_id"
        contextPath="forms.design.registration_mode"
        required
      >
        <el-select-wrap
          v-model="form.registration_mode_id"
          name="registration_mode_id"
          :data-vv-as="trans('forms.design.registration_mode.label')"
          v-validate="'required'"
          :options="registrationModeList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="expected_annual_participant_number"
        contextPath="forms.design.expected_annual_participant_number"
        required
      >
        <input-wrap
          v-model="form.expected_annual_participant_number"
          name="expected_annual_participant_number"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.design.expected_annual_participant_number.label')"
          :min="1"
          :max="500000"
          type="number"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="content">
      <span slot="label" :class="{'is-error': errorTabs.includes('content') }">
        {{ trans('forms.design.tabs.content') }}
      </span>
      <h3>{{ trans('forms.design.tabs.content') }}</h3>
      <form-section-group
        v-model="form.applicable_policies"
        entityForm="design"
        entitySection="applicable-policy"
      />
      <el-form-item-wrap
        prop="content_source_type_id"
        contextPath="forms.design.content_source_type"
        required
      >
        <el-select-wrap
          v-model="form.content_source_type_id"
          name="content_source_type_id"
          :data-vv-as="trans('forms.design.content_source_type.label')"
          v-validate="'required'"
          :options="contentSourceTypeList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="content_source_codes"
        contextPath="forms.design.content_source_codes"
        :required="isContentSourceCodesFieldRequired"
      >
        <learning-product-search
          name="content_source_codes"
          :validate="{ required: isContentSourceCodesFieldRequired }"
          :dataVvAs="trans('forms.design.content_source_codes.label')"
          :disabled="!isContentSourceCodesFieldRequired"
          :model.sync="form.content_source_codes"
          :codes="codes"
          multiple
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="classifications">
      <span slot="label" :class="{'is-error': errorTabs.includes('classifications') }">
        {{ trans('forms.design.tabs.classifications') }}
      </span>
      <h3>{{ trans('forms.design.tabs.classifications') }}</h3>
      <el-form-item-wrap
        prop="topics"
        contextPath="forms.design.topics"
        required
      >
        <el-tree-wrap
          v-model="form.topics"
          name="topics"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.topics.label')"
          :data="topicList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="programs"
        contextPath="forms.design.programs"
      >
        <el-tree-wrap
          v-model="form.programs"
          name="programs"
          v-validate="''"
          :data-vv-as="trans('forms.design.programs.label')"
          :data="programList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="series"
        contextPath="forms.design.series"
      >
        <el-tree-wrap
          v-model="form.series"
          name="series"
          v-validate="''"
          :data-vv-as="trans('forms.design.series.label')"
          :data="seriesList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="curriculum_type_id"
        contextPath="forms.design.curriculum_type"
        required
      >
        <el-select-wrap
          v-model="form.curriculum_type_id"
          name="curriculum_type_id"
          :data-vv-as="trans('forms.design.curriculum_type.label')"
          v-validate="'required'"
          :options="curriculumTypeList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="management_accountability_framework_areas"
        contextPath="forms.design.management_accountability_framework_areas"
        required
      >
        <el-tree-wrap
          v-model="form.management_accountability_framework_areas"
          name="management_accountability_framework_areas"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.management_accountability_framework_areas.label')"
          :data="managementAccountabilityFrameworkAreaList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="competencies"
        contextPath="forms.design.competencies"
      >
        <el-select-wrap
          v-model="form.competencies"
          name="competencies"
          :data-vv-as="trans('forms.design.competencies.label')"
          v-validate="''"
          :options="competenceList"
          multiple
          :multiple-limit="3"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="target_audience_knowledge_level_id"
        contextPath="forms.design.target_audience_knowledge_level"
        required
      >
        <el-select-wrap
          v-model="form.target_audience_knowledge_level_id"
          name="target_audience_knowledge_level_id"
          :data-vv-as="trans('forms.design.target_audience_knowledge_level.label')"
          v-validate="'required'"
          :options="targetAudienceKnowledgeLevelList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="communities"
        contextPath="forms.design.communities"
        required
      >
        <el-tree-wrap
          v-model="form.communities"
          name="communities"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.communities.label')"
          :data="communityList"
          sorted
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="prerequisites">
      <span slot="label" :class="{'is-error': errorTabs.includes('prerequisites') }">
        {{ trans('forms.design.tabs.prerequisites') }}
      </span>
      <h3>{{ trans('forms.design.tabs.prerequisites') }}</h3>
      <el-form-item-wrap
        prop="mandatory_prerequisites"
        contextPath="forms.design.mandatory_prerequisites"
      >
        <learning-product-search
          name="mandatory_prerequisites"
          :validate="''"
          :dataVvAs="trans('forms.design.mandatory_prerequisites.label')"
          :model.sync="form.mandatory_prerequisites"
          :codes="codes"
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_prerequisites"
        contextPath="forms.design.recommended_prerequisites"
      >
        <learning-product-search
          name="recommended_prerequisites"
          :validate="''"
          :dataVvAs="trans('forms.design.recommended_prerequisites.label')"
          :model.sync="form.recommended_prerequisites"
          :codes="codes"
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="complementary_learning_products"
        contextPath="forms.design.complementary_learning_products"
      >
        <learning-product-search
          name="complementary_learning_products"
          :validate="''"
          :dataVvAs="trans('forms.design.complementary_learning_products.label')"
          :model.sync="form.complementary_learning_products"
          :codes="codes"
          multiple
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="clients">
      <span slot="label" :class="{'is-error': errorTabs.includes('clients') }">
        {{ trans('forms.design.tabs.clients') }}
      </span>
      <h3>{{ trans('forms.design.tabs.clients') }}</h3>
      <el-form-item-wrap
        contextPath="forms.design.prescribed_by_tbs"
        prop="prescribed_by_tbs"
        required
      >
        <yes-no
          v-model="form.prescribed_by_tbs"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.prescribed_by_tbs.label')"
          name="prescribed_by_tbs"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="prescribed_by_departments"
        contextPath="forms.design.prescribed_by_departments"
      >
        <el-select-wrap
          v-model="form.prescribed_by_departments"
          name="prescribed_by_departments"
          :disabled="form.prescribed_by_tbs"
          :data-vv-as="trans('forms.design.prescribed_by_departments.label')"
          v-validate="''"
          :options="departmentList"
          multiple
          filterable
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_by_departments"
        contextPath="forms.design.recommended_by_departments"
      >
        <el-select-wrap
          v-model="form.recommended_by_departments"
          name="recommended_by_departments"
          :disabled="form.prescribed_by_tbs"
          :data-vv-as="trans('forms.design.recommended_by_departments.label')"
          v-validate="''"
          :options="departmentList"
          multiple
          filterable
          sorted
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="timeframe">
      <span slot="label" :class="{'is-error': errorTabs.includes('timeframe') }">
        {{ trans('forms.design.tabs.timeframe') }}
      </span>
      <h3>{{ trans('forms.design.tabs.timeframe') }}</h3>
      <el-form-item-wrap
        prop="expected_pilot_start_date"
        contextPath="forms.design.expected_pilot_start_date"
      >
        <el-date-picker
          v-model="form.expected_pilot_start_date"
          v-validate="''"
          :data-vv-as="trans('forms.design.expected_pilot_start_date.label')"
          name="expected_pilot_start_date"
          :picker-options="expectedPilotStartDateRange"
          type="date"
          value-format="yyyy-MM-dd"
        />
        <form-error name="expected_pilot_start_date"/>
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="expected_launch_date"
        contextPath="forms.design.expected_launch_date"
        required
      >
        <el-date-picker
          v-model="form.expected_launch_date"
          v-validate="'required'"
          :data-vv-as="trans('forms.design.expected_launch_date.label')"
          name="expected_launch_date"
          :picker-options="expectedLaunchDateRange"
          type="date"
          value-format="yyyy-MM-dd"
        />
        <form-error name="expected_launch_date"/>
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_review_interval"
        contextPath="forms.design.recommended_review_interval"
        required
      >
        <input-wrap
          v-model="form.recommended_review_interval"
          name="recommended_review_interval"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.design.recommended_review_interval.label')"
          :min="1"
          :max="60"
          type="number"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="costs">
      <span slot="label" :class="{'is-error': errorTabs.includes('costs') }">
        {{ trans('forms.design.tabs.costs') }}
      </span>
      <h3>{{ trans('forms.design.tabs.costs') }}</h3>
      <form-section-group
        v-model="form.additional_costs"
        entityForm="design"
        entitySection="additional-cost"
      />
      <el-form-item-wrap
        prop="internal_order"
        contextPath="forms.design.internal_order"
      >
        <input-wrap
          v-model="form.internal_order"
          name="internal_order"
          v-validate="{ regex: /[A-Z][0-9]{1,6}/ }"
          :data-vv-as="trans('forms.design.internal_order.label')"
          :placeholder="trans('forms.design.internal_order.hint')"
          v-mask="'A######'"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="comments">
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.design.tabs.comments') }}
      </span>
      <h3>{{ trans('forms.design.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.design.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.design.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapState } from 'vuex';

  import Form from '../form.vue';

  import LearningProduct from '@/store/models/Learning-Product';

  import LearningProductSearch from '../learning-product-search';

  const loadData = async () => {
    const requests = [];
    requests.push(
      store.dispatch('entities/learningProducts/loadCodes'),
      store.dispatch('lists/loadLists', [
        'outcome-type',
        'possible-offering-type',
        'registration-mode',
        'content-source-type',
        'topic',
        'program',
        'series',
        'curriculum-type',
        'management-accountability-framework-area',
        'competency',
        'target-audience-knowledge-level',
        'community',
        'department'
      ])
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'design',

    extends: Form,

    components: { LearningProductSearch },

    data() {
      const that = this;
      return {
        expectedPilotStartDateRange: {
          disabledDate(time) {
            const potentialPilotStartDate = time.getTime();
            if (that.form.expected_launch_date) {
              let launchDate = new Date(that.form.expected_launch_date);
              // we need to increment the date by 1
              // because of the fact that when we create a date, it sets the time to 00:00:00
              let actualLaunchDate = new Date(launchDate.setDate(launchDate.getDate() + 1));
              return potentialPilotStartDate < new Date('2000-01-01') || potentialPilotStartDate > actualLaunchDate;
            } else {
              return potentialPilotStartDate < new Date('2000-01-01') || potentialPilotStartDate > new Date('2051-01-01');
            }
          }
        },
        expectedLaunchDateRange: {
          disabledDate(time) {
            const potentialLaunchDate = time.getTime();
            if (that.form.expected_pilot_start_date) {
              let pilotStartDate = new Date(that.form.expected_pilot_start_date);
              // increment the date by 1
              // e.g.: pilotStartDate = Feb. 18th
              //       launchDate = Feb. 19th
              // expectedLaunchDateRange = Feb. 19th - 2050-12-31
              return potentialLaunchDate < pilotStartDate || potentialLaunchDate > new Date('2051-01-01');
            } else {
              return potentialLaunchDate < new Date('2000-01-01') || potentialLaunchDate > new Date('2051-01-01');
            }
          }
        }
      };
    },

    computed: {
      ...mapState('entities/learningProducts', [
        'codes'
      ]),
      isContentSourceCodesFieldRequired() {
        const contentSourceType = _.find(this.contentSourceTypeList, ['id', this.form.content_source_type_id]);
        return contentSourceType ? contentSourceType.name_key !== 'new' : false;
      },
      learningProduct() {
        return LearningProduct.find(this.$route.params.entityId);
      },
      outcomeTypesList() {
        return this.getList('outcome-type');
      },
      possibleOfferingTypesList() {
        return this.getList('possible-offering-type');
      },
      registrationModeList() {
        return this.getList('registration-mode');
      },
      contentSourceTypeList() {
        return this.getList('content-source-type');
      },
      topicList() {
        return this.getList('topic');
      },
      programList() {
        return this.getList('program');
      },
      seriesList() {
        return this.getList('series');
      },
      curriculumTypeList() {
        return this.getList('curriculum-type');
      },
      managementAccountabilityFrameworkAreaList() {
        return this.getList('management-accountability-framework-area');
      },
      competenceList() {
        return this.getList('competency');
      },
      targetAudienceKnowledgeLevelList() {
        return this.getList('target-audience-knowledge-level');
      },
      communityList() {
        return this.getList('community');
      },
      complementaryLearningProductList() {
        return this.getList('complementary-learning-product');
      },
      departmentList() {
        return this.getList('department');
      }
    },

    watch: {
      form() {
        this.formatLists();
      },
      'form.content_source_type_id'(id) {
        if (_.find(this.contentSourceTypeList, ['id', id]).name_key === 'new') {
          this.form.content_source_codes = [];
        }
      },
      'form.prescribed_by_tbs'(isPrescribedByTbs) {
        if (isPrescribedByTbs) {
          this.form.prescribed_by_departments = [];
          this.form.recommended_by_departments = [];
        }
      }
    },

    methods: {
      loadData,

      formatLists() {
        // replace our internal organizational_units with only the ids
        // since ElementUI only need ids to populate the selected options
        this.form.content_source_codes = _.map(this.form.content_source_codes, 'id');
        this.form.mandatory_prerequisites = _.map(this.form.mandatory_prerequisites, 'id');
        this.form.recommended_prerequisites = _.map(this.form.recommended_prerequisites, 'id');
        this.form.complementary_learning_products = _.map(this.form.complementary_learning_products, 'id');
      }
    },

    created() {
      this.formatLists();
    }
  };
</script>

<style lang="scss">

</style>
