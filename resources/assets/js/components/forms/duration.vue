<template>
  <div class="duration">
    <input-wrap
      v-model="innerHoursModel"
      name="hours"
      v-validate="innerHoursValidate"
      :data-vv-as="trans('entities.general.hours')"
      type="number"
      :min="0"
      :max="hoursMaxValue"
      hideErrors
    >
      <template slot="prepend">{{ trans('entities.general.hours') }}</template>
    </input-wrap>
    <input-wrap
      v-model="innerMinsModel"
      name="minutes"
      v-validate="innerMinsValidate"
      :data-vv-as="trans('entities.general.minutes')"
      type="number"
      :min="0"
      :max="innerHoursModel === hoursMaxValue?0:minsMaxValue"
      hideErrors
    >
      <template slot="prepend">{{ trans('entities.general.minutes') }}</template>
    </input-wrap>
    <input-wrap
      v-model="innerSecsModel"
      name="seconds"
      v-validate="innerSecsValidate"
      :data-vv-as="trans('entities.general.seconds')"
      type="number"
      :min="0"
      :max="innerHoursModel === hoursMaxValue?0:secsMaxValue"
      hideErrors
    >
      <template slot="prepend">{{ trans('entities.general.seconds') }}</template>
    </input-wrap>
    <form-error name="hours"></form-error>
    <form-error name="minutes"></form-error>
    <form-error name="seconds"></form-error>
  </div>
</template>

<script>
  import FormError from './error.vue';
  import InputWrap from './input-wrap.vue';

  export default {
    name: 'duration',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError, InputWrap },

    props: {
      isRequired: {
        type: Boolean,
        default: false
      },
      hoursMaxValue: {
        type: Number,
        default: 24
      },
      minsMaxValue: {
        type: Number,
        default: 59
      },
      secsMaxValue: {
        type: Number,
        default: 59
      },
      value: {
        type: String | Number
      }
    },

    data() {
      return {
        innerHoursModel: null,
        innerMinsModel: null,
        innerSecsModel: null
      };
    },

    computed: {
      innerHoursValidate() {
        return {
          min_value: 0,
          max_value: this.hoursMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      innerMinsValidate() {
        return {
          min_value: 0,
          max_value: this.innerHoursModel === this.hoursMaxValue?0:this.minsMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      innerSecsValidate() {
        return {
          min_value: 0,
          max_value: this.innerHoursModel === this.hoursMaxValue?0:this.secsMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      totalDuration() {
        const hours = this.innerHoursModel;
        const mins = this.innerMinsModel;
        const secs = this.innerSecsModel;
        return (hours * 60 * 60) + (mins * 60) + secs;
      },
    },

    watch: {
      value: {
        immediate: true,
        handler() {
          this.innerHoursModel = Math.floor(this.value / 3600);
          this.innerMinsModel = Math.floor((this.value % 3600) / 60);
          this.innerSecsModel = this.value % 60;
        }
      },
      // this makes sure that when the hours are at max
      // that we reset the mins and secs to 0
      innerHoursModel() {
        if (this.innerHoursModel === this.hoursMaxValue) {
          this.innerMinsModel = 0;
          this.innerSecsModel = 0;
        }
      },
      totalDuration(val) {
        // affect the computed value so that our field is in sync
        this.$emit('input', val);
      }
    }
  };
</script>

<style lang="scss">
  .duration .input-wrap {
    margin-right: 20px;
    display: inline-block;
    & + .input-wrap {
      margin-top: 0;
    }
  }
</style>
