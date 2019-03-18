<template>
  <el-tabs v-bind="$attrs" @tab-click="onTabClick">
    <el-tab-pane data-name="instructors"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('instructors') }">
        {{ trans('forms.operational_details.tabs.instructors') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.instructors') }}</h3>
      <form-section-group
        v-model="form.instructors"
        entityForm="operational-details"
        entitySection="instructor"
        :min="1"
      />
    </el-tab-pane>
    <el-tab-pane data-name="guest_speakers"
      v-if="isCourseInstructorLed()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('guest_speakers') }">
        {{ trans('forms.operational_details.tabs.guest_speakers') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.guest_speakers') }}</h3>
      <form-section-group
        v-model="form.guest_speakers"
        entityForm="operational-details"
        entitySection="guest-speaker"
      />
    </el-tab-pane>
    <el-tab-pane data-name="offering_details"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('offering_details') }">
        {{ trans('forms.operational_details.tabs.offering_details') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.offering_details') }}</h3>
      <el-form-item-wrap
        prop="number_of_virtual_producers_per_offering"
        contextPath="forms.operational_details.number_of_virtual_producers_per_offering"
        :required="isNumberOfVirtualProducersPerOfferingRequired"
        v-if="isCourseInstructorLed()"
      >
        <input-wrap
          :disabled="!isNumberOfVirtualProducersPerOfferingRequired"
          v-model="form.number_of_virtual_producers_per_offering"
          name="number_of_virtual_producers_per_offering"
          v-validate="{ required: isNumberOfVirtualProducersPerOfferingRequired, numeric: true }"
          :data-vv-as="trans('forms.operational_details.number_of_virtual_producers_per_offering.label')"
          type="number"
          :min="0"
          :max="10"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="minimum_number_of_participant_per_offering"
        contextPath="forms.operational_details.minimum_number_of_participant_per_offering"
        required
        v-if="isCourseInstructorLed() || isCourseDistance()"
      >
        <input-wrap
          v-model="form.minimum_number_of_participant_per_offering"
          name="minimum_number_of_participant_per_offering"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.operational_details.minimum_number_of_participant_per_offering.label')"
          type="number"
          :min="0"
          :max="100"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="maximum_number_of_participant_per_offering"
        contextPath="forms.operational_details.maximum_number_of_participant_per_offering"
        required
        v-if="isCourseInstructorLed() || isCourseDistance()"
      >
        <input-wrap
          v-model="form.maximum_number_of_participant_per_offering"
          name="maximum_number_of_participant_per_offering"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.operational_details.maximum_number_of_participant_per_offering.label')"
          type="number"
          :min="1"
          :max="100"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="optimal_number_of_participant_per_offering"
        contextPath="forms.operational_details.optimal_number_of_participant_per_offering"
        required
        v-if="isCourseInstructorLed() || isCourseDistance()"
      >
        <input-wrap
          v-model="form.optimal_number_of_participant_per_offering"
          name="optimal_number_of_participant_per_offering"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.operational_details.optimal_number_of_participant_per_offering.label')"
          type="number"
          :min="1"
          :max="100"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="waiting_list_maximum_size"
        contextPath="forms.operational_details.waiting_list_maximum_size"
        required
        v-if="isCourseInstructorLed() || isCourseDistance()"
      >
        <input-wrap
          v-model="form.waiting_list_maximum_size"
          name="waiting_list_maximum_size"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="trans('forms.operational_details.waiting_list_maximum_size.label')"
          type="number"
          :min="0"
          :max="99"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="session_template"
        contextPath="forms.operational_details.session_template"
        required
        v-if="isCourseInstructorLed() || isCourseDistance()"
      >
        <input-wrap
          v-model="form.session_template"
          name="session_template"
          v-validate="'required'"
          :data-vv-as="trans('forms.operational_details.session_template.label')"
          maxlength="250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="external_delivery"
        contextPath="forms.operational_details.external_delivery"
        required
        v-if="isCourseInstructorLed()"
      >
        <yes-no
          v-model="form.external_delivery"
          v-validate="'required'"
          :data-vv-as="trans('forms.operational_details.external_delivery.label')"
          name="external_delivery"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="rooms"
      v-if="isCourseInstructorLed()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('rooms') }">
        {{ trans('forms.operational_details.tabs.rooms') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.rooms') }}</h3>
      <form-section-group
        v-model="form.rooms"
        entityForm="operational-details"
        entitySection="room"
        :min="1"
        :data="{
          roomUsageList,
          roomTypeList,
          roomLayoutList
        }"
      />
    </el-tab-pane>
    <el-tab-pane data-name="materials"
      v-if="isCourseInstructorLed()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('materials') }">
        {{ trans('forms.operational_details.tabs.materials') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.materials') }}</h3>
      <form-section-group
        v-model="form.materials"
        entityForm="operational-details"
        entitySection="material"
        :data="{
          materialItemList,
          materialDenominatorList,
          materialLocationList
        }"
      />
    </el-tab-pane>
    <el-tab-pane data-name="documents"
      v-if="isCourseInstructorLed() || isCourseDistance()"
    >
      <span slot="label" :class="{'is-error': errorTabs.includes('documents') }">
        {{ trans('forms.operational_details.tabs.documents') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.documents') }}</h3>
      <form-section-group
        v-model="form.documents"
        entityForm="operational-details"
        entitySection="document"
        :data="{
          documentCategoryList,
          documentDenominatorList
        }"
      />
    </el-tab-pane>
    <el-tab-pane data-name="gc_campus">
      <span slot="label" :class="{'is-error': errorTabs.includes('gc_campus') }">
        {{ trans('forms.operational_details.tabs.gc_campus') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.gc_campus') }}</h3>
      <el-form-item-wrap
        prop="should_be_published"
        contextPath="forms.operational_details.should_be_published"
        required
      >
        <yes-no
          v-model="form.should_be_published"
          v-validate="'required'"
          :data-vv-as="trans('forms.operational_details.should_be_published.label')"
          name="should_be_published"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="summary_content"
        contextPath="forms.operational_details.summary_content"
      >
        <input-wrap
          v-model="form.summary_content"
          name="summary_content"
          v-validate="''"
          :disabled="!form.should_be_published"
          :data-vv-as="trans('forms.operational_details.summary_content.label')"
          maxlength="280"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="note_content"
        contextPath="forms.operational_details.note_content"
      >
        <input-wrap
          v-model="form.note_content"
          name="note_content"
          v-validate="''"
          :disabled="!form.should_be_published"
          :data-vv-as="trans('forms.operational_details.note_content.label')"
          maxlength="500"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="disclaimer_content"
        contextPath="forms.operational_details.disclaimer_content"
      >
        <input-wrap
          v-model="form.disclaimer_content"
          name="disclaimer_content"
          v-validate="''"
          :disabled="!form.should_be_published"
          :data-vv-as="trans('forms.operational_details.disclaimer_content.label')"
          maxlength="500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="comments">
      <span slot="label" :class="{'is-error': errorTabs.includes('comments') }">
        {{ trans('forms.operational_details.tabs.comments') }}
      </span>
      <h3>{{ trans('forms.operational_details.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.operational_details.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="trans('forms.operational_details.comments.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import { mapState, mapGetters } from 'vuex';

  import Form from '../form.vue';

  const loadData = async () => {
    const requests = [];
    requests.push(
      store.dispatch('lists/loadLists', [
        'room-usage',
        'room-type',
        'room-layout',
        'material-item',
        'material-denominator',
        'material-location',
        'document-category',
        'document-denominator',
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
    name: 'operational-details',

    extends: Form,

    data() {
      return {};
    },

    computed: {
      ...mapGetters('entities/learningProducts', [
        'isCourse',
        'isCourseInstructorLed',
        'isCourseDistance',
        'isCourseOnlineSelfPaced'
      ]),
      ...mapState('entities/forms', [
        'meta'
      ]),

      metaInfo() {
        return this.meta.design;
      },

      isNumberOfVirtualProducersPerOfferingRequired() {
        // Mandatory if one of design.possible_offering_type
        // is included in ("virtual", "simultaneous-virtual-and-in-person").
        const possibleOfferingTypes = _.map(this.metaInfo.possible_offering_types, 'name_key');
        const isRequired = ['virtual', 'simultaneous-virtual-and-in-person'].some(s => s === possibleOfferingTypes);

        if (!isRequired) {
          this.form.number_of_virtual_producers_per_offering = null;
        }

        return isRequired;
      },

      roomUsageList() {
        return this.getList('room-usage');
      },
      roomTypeList() {
        return this.getList('room-type');
      },
      roomLayoutList() {
        return this.getList('room-layout');
      },
      materialItemList() {
        return this.getList('material-item');
      },
      materialDenominatorList() {
        return this.getList('material-denominator');
      },
      materialLocationList() {
        return this.getList('material-location');
      },
      documentCategoryList() {
        return this.getList('document-category');
      },
      documentDenominatorList() {
        return this.getList('document-denominator');
      }
    },

    watch: {
      'form.should_be_published'(shouldBePublished) {
        if (!shouldBePublished) {
          this.form.summary_content = null;
          this.form.note_content = null;
          this.form.disclaimer_content = null;
        }
      }
    },

    methods: {
      loadData
    }
  };
</script>

<style lang="scss">

</style>
