<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="participants"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('participants') }">
        {{ trans('forms.offering_and_enrolment_estimates.tabs.participants') }}
      </span>
      <h3>{{ trans('forms.offering_and_enrolment_estimates.tabs.participants') }}</h3>
      <el-form-item-wrap
        prop="estimated_average_annual_participant_number"
        contextPath="forms.offering_and_enrolment_estimates.estimated_average_annual_participant_number"
        required
      >
        <input-wrap
          v-model="form.estimated_average_annual_participant_number"
          name="estimated_average_annual_participant_number"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.offering_and_enrolment_estimates.estimated_average_annual_participant_number.label')"
          type="number"
          :min="1"
          :max="500000"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="regions"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('regions') }">
        {{ trans('forms.offering_and_enrolment_estimates.tabs.regions') }}
      </span>
      <h3>{{ trans('forms.offering_and_enrolment_estimates.tabs.regions') }}</h3>
      <form-section-group
        v-model="form.offering_regions"
        entityForm="offering-and-enrolment-estimates"
        entitySection="region"
        :min="1"
        :data="{
          regionList
        }"
      />
    </el-tab-pane>
    <el-tab-pane data-name="comments"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.offering_and_enrolment_estimates.tabs.comments') }}
      </span>
      <h3>{{ trans('forms.offering_and_enrolment_estimates.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.offering_and_enrolment_estimates.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.offering_and_enrolment_estimates.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapGetters } from 'vuex';

  import Form from '../form.vue';

  const loadData = async () => {
    const requests = [];
    requests.push(
      store.dispatch('lists/loadLists', [
        'region',
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
    name: 'offering-and-enrolment-estimates',

    extends: Form,

    data() {
      return {};
    },

    computed: {
      ...mapGetters('entities/learningProducts', [
        'isCourse',
        'isCourseInstructorLed',
        'isCourseDistance'
      ]),

      regionList() {
        return this.getList('region');
      }
    },

    methods: {
      loadData
    }
  };
</script>

<style lang="scss">

</style>
